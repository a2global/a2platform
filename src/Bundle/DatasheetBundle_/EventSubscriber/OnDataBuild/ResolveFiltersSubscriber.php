<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber\OnDataBuild;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
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
        $defaultParameters = $this->getDefaultFilterParameters($event->getDatasheet());
        $queryParameters = $datasheetParameters['filter'] ?? [];
        $mergedParameters = array_merge($defaultParameters, $queryParameters);
        $filtersParameters = new ParameterBag($mergedParameters);

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

    protected function getDefaultFilterParameters(DatasheetExposed $datasheet)
    {
        $params = [];

        /** @var DatasheetFilterInterface $filter */
        foreach ($this->datasheetFilterRegistry->get() as $filter) {
            if (!$filter->supports($datasheet)) {
                continue;
            }

            foreach ($filter->getForm(new ParameterBag([])) as $form) {
                $params[$filter->getName()][$form['name']] = $form['value'];
                $params[$filter->getName()]['type'] = $filter->getName();
            }
        }

        return $params;
    }
}