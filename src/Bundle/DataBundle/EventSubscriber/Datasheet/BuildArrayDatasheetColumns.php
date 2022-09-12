<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\ObjectDataType;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\StringDataType;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuildArrayDatasheetColumns implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'array';

    public function buildColumns(OnDatasheetBuildEvent $event)
    {
        if (!is_array($event->getDatasheet()->getDatasource())) {
            return;
        }
        $columns = [];

        foreach ($event->getDataCollection()->getFields() as $field) {
            $columns[] = new DatasheetColumn(new ObjectDataType(), $field);
        }
        $event->getDatasheet()->setColumns($columns);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['buildColumns', 200],
        ];
    }
}