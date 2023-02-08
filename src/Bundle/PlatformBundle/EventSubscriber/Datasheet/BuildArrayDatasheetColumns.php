<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetColumn;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\ObjectDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet\OnDatasheetBuildEvent;
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

        foreach ($event->getDataReader()->getFields() as $field) {
            $columns[] = (new DatasheetColumn($field))->setType(new ObjectDataType());
        }
        $event->getDatasheet()->setColumns($columns);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['buildColumns', 600],
        ];
    }
}