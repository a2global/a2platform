<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnAfterBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\DatasheetFilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Registry\DatasheetFilterRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

class BuildFiltersFormSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected DatasheetFilterRegistry $datasheetFilterRegistry,
        protected RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnAfterBuildEvent::class => ['buildFiltersForm', 470],
        ];
    }

    public function buildFiltersForm(OnAfterBuildEvent $event)
    {
        $datasheetParameters = $this->requestStack
            ->getCurrentRequest()
            ->query
            ->get(sprintf('ds%s', $event->getDatasheet()->getId()), []);
        $filtersParameters = new ParameterBag($datasheetParameters['filter'] ?? []);

        /**
         * Context contains null + all datasheet columns
         * to interate available filters through every column
         * and for datasheet (when column is null)
         */
        $context = $event->getDatasheet()->getColumns();
        array_unshift($context, null);

        foreach ($context as $column) {

            /** @var DatasheetFilterInterface $filter */
            foreach ($this->datasheetFilterRegistry->get() as $filter) {
                $parametersKey = $column ? ($column->getName() . '_' . $filter->getName()) : $filter->getName();
                $filterParameters = new ParameterBag($filtersParameters->get($parametersKey, []));

                if (!$filter->supports($event->getDatasheet(), $column)) {
                    continue;
                }
                $fields = [];
                $filterKey = ($column ? $column->getName() . '_' : '') . $filter->getName();

                foreach ($filter->getForm($filterParameters) as $name => $value) {
                    $fields[] = [
                        'name' => sprintf('ds%s[filter][%s][%s]', $event->getDatasheet()->getId(), $filterKey, $name),
                        'value' => $value,
                    ];
                }
                $fields[] = [
                    'name' => sprintf('ds%s[filter][%s][type]', $event->getDatasheet()->getId(), $filterKey),
                    'value' => $filter->getName(),
                ];

                if ($column) {
                    $fields[] = [
                        'name' => sprintf('ds%s[filter][%s][column]', $event->getDatasheet()->getId(), $filterKey),
                        'value' => $column->getName(),
                    ];
                }
                $event->getDatasheet()->addFilterForm($fields, $column);
            }
        }
    }
}