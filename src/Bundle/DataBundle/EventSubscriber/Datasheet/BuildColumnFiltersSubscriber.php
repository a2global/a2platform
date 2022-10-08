<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetColumnFiltersBuildEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuildColumnFiltersSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'all';

    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function buildColumnFilters(OnDatasheetBuildEvent $event)
    {
        foreach ($event->getDatasheet()->getColumns() as $column) {
            $this->eventDispatcher->dispatch(
                new OnDatasheetColumnFiltersBuildEvent(
                    $event->getDatasheet(),
                    $column,
                    $event->getDataReader(),
                )
            );
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['buildColumnFilters', 500],
        ];
    }
}