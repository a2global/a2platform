<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Filter\SortDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Manager\DatasheetParametersManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddSortFilterEventSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'queryBuilder';

    public function __construct(
        protected DatasheetParametersManager $parametersManager,
    ) {
    }

    public function addFilter(OnDatasheetBuildEvent $event)
    {
        $parameters = $this->parametersManager->getDatasheetFilterParameters(
            $event->getDatasheet(),
            SortDataFilter::getName(),
        );
        $filter = new SortDataFilter();
        $this->parametersManager->applyParameters($filter, $parameters);
        $event->getDataReader()->addFilter($filter);
        $this->parametersManager->addFilterToDatasheet($event->getDatasheet(), $filter);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['addFilter', 510],
        ];
    }
}