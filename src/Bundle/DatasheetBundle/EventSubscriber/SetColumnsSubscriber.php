<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetColumnsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnColumnsBuildEvent::class => ['setColumns', 100],
        ];
    }

    public function setColumns(OnColumnsBuildEvent $event)
    {
        foreach ($event->getColumns() as $column) {
            $event->getDatasheet()->addColumn($column);
        }
    }
}
