<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Registry\DataReaderRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetDataEventSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'all';

    public function __construct(
        protected DataReaderRegistry $dataReaderRegistry,
    ) {
    }

    public function setDataReader(OnDatasheetBuildEvent $event)
    {
        $event->getDatasheet()->setData($event->getDataReader()->readData());
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['setDataReader', 300],
        ];
    }
}