<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Registry\DataReaderRegistry;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetDataReaderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['setDataReader', 500],
        ];
    }

    public function __construct(
        protected DataReaderRegistry $dataReaderRegistry,
    ) {
    }

    public function setDataReader(OnDataBuildEvent $event)
    {
        $dataSource = $event->getDatasheet()->getConfig()['dataSource'];
        $dataReader = $this->findSuitableDataReader($dataSource);
        $dataReader->setSource($dataSource);
        $event->setDataReader($dataReader);
    }

    protected function findSuitableDataReader($source): DataReaderInterface
    {
        /** @var DataReaderInterface $dataReader */
        foreach ($this->dataReaderRegistry->get() as $dataReader) {
            if ($dataReader->supports($source)) {
                return $dataReader;
            }
        }
    }
}