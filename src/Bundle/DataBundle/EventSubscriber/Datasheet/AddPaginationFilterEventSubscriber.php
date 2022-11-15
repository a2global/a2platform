<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Builder\DataFilterHashBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Manager\DatasheetParametersManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddPaginationFilterEventSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'all';

    public function __construct(
        protected DatasheetParametersManager $parametersManager,
    ) {
    }

    public function addFilter(OnDatasheetBuildEvent $event)
    {
        $parameters = $this->parametersManager->getDatasheetFilterParameters(
            $event->getDatasheet(),
            PaginationDataFilter::getName(),
        );
        $filters = [];

        foreach ($event->getDataReader()->getAllFilters() as $item) {
            $filters[] = reset($item);
        }
        $currentFiltersHash = DataFilterHashBuilder::build($filters);
        $filter = new PaginationDataFilter();

        if (empty($parameters['hash']) || ($parameters['hash'] === $currentFiltersHash)) {
            $this->parametersManager->applyParameters($filter, $parameters);
        }
        $filter->setHash($currentFiltersHash);
        $event->getDataReader()->addFilter($filter);
        $this->parametersManager->addFilterToDatasheet($event->getDatasheet(), $filter);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            /**
             * Should go after all filters, except SortDataFilter
             * in order to get proper 'other filters hash'
             */
            OnDatasheetBuildEvent::class => ['addFilter', 520],
        ];
    }
}