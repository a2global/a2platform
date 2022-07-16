<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\DatasheetFilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Registry\DatasheetFilterRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

class ResolveFiltersSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected DatasheetFilterRegistry $datasheetFilterRegistry,
        protected RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['resolveFilters', 470],
        ];
    }

    public function resolveFilters(OnDataBuildEvent $event)
    {
        $datasheetParameters = $this->requestStack
            ->getCurrentRequest()
            ->query
            ->get(sprintf('ds%s', $event->getDatasheet()->getId()), []);
        $filtersParameters = new ParameterBag($datasheetParameters['filter'] ?? []);

        foreach ($filtersParameters as $filterParameters) {
            $filterParameters = new ParameterBag($filterParameters);
            $columnName = $filterParameters->get('column');

            if ($columnName) {
                $filterParameters->remove('column');
            }

            /** @var DatasheetFilterInterface $filter */
            foreach ($this->datasheetFilterRegistry->get() as $filter) {
                if ($filterParameters->get('type') != $filter->getName()) {
                    continue;
                }

                if (!$filter->isDefined($filterParameters)) {
                    continue;
                }
                $event->getDatasheet()->addFilter($filter->getDataFilter($filterParameters, $columnName));
            }
        }
    }
}