<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

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

    public function addPaginationFilter(OnDatasheetBuildEvent $event)
    {
        $parameters = $this->parametersManager->getDatasheetFilterParameters(
            $event->getDatasheet(),
            PaginationDataFilter::getName(),
        );
        $filter = new PaginationDataFilter();
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
            OnDatasheetBuildEvent::class => ['addPaginationFilter', 700],
        ];
    }
}