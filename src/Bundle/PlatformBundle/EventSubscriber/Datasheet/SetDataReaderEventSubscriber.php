<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Exception\DatasheetBuildException;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Registry\DataReaderRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetDataReaderEventSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'all';

    public function __construct(
        protected DataReaderRegistry $dataReaderRegistry,
    ) {
    }

    public function setDataReader(OnDatasheetBuildEvent $event)
    {
        /** @var DataReaderInterface $dataReader */
        foreach ($this->dataReaderRegistry->get() as $dataReader) {
            if ($dataReader->supports($event->getDatasheet()->getDatasource())) {
                return $event->setDataReader($dataReader->setSource($event->getDatasheet()->getDatasource()));
            }
        }

        throw new DatasheetBuildException('Failed to set data reader for ' . $event->getDatasheet()->getTitle());
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['setDataReader', 900],
        ];
    }
}