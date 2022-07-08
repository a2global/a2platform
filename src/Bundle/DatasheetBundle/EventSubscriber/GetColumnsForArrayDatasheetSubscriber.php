<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GetColumnsForArrayDatasheetSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnColumnsBuildEvent::class => ['getColumnsForArrayDatasheet', 500],
        ];
    }

    public function getColumnsForArrayDatasheet(OnColumnsBuildEvent $event)
    {
        if (!is_array($event->getDatasheet()->getConfig()['dataSource'])) {
            return;
        }
        $columns = [];

        foreach ($event->getDatasheet()->getData()->getFields() as $fieldName) {
            $columns[$fieldName] = new DatasheetColumn($fieldName);
        }
        $event->setColumns($columns);
    }
}
