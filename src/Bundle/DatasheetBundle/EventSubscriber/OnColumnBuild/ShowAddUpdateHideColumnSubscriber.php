<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber\OnColumnBuild;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ShowAddUpdateHideColumnSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnColumnsBuildEvent::class => ['showAddUpdateHideColumn', 400],
        ];
    }

    public function showAddUpdateHideColumn(OnColumnsBuildEvent $event)
    {
        $columns = $event->getColumns();

        if ($event->getDatasheet()->getConfig()['columns']['show'] ?? false) {
            foreach ($columns as $name => $column) {
                if (!in_array($name, $event->getDatasheet()->getConfig()['columns']['show'])) {
                    unset($columns[$name]);
                }
            }
        }

        /** @var DatasheetColumn $column */
        foreach ($event->getDatasheet()->getConfig()['columns']['add'] ?? [] as $column) {
            $columns[$column->getName()] = $column;
        }

        /** @var DatasheetColumn $column */
        foreach ($event->getDatasheet()->getConfig()['columns']['update'] ?? [] as $column) {
            $columns[$column->getName()] = $column;
        }

        /** @var DatasheetColumn $column */
        foreach ($event->getDatasheet()->getConfig()['columns']['hide'] ?? [] as $columnName) {
            unset($columns[$columnName]);
        }

        $event->setColumns($columns);
    }
}
