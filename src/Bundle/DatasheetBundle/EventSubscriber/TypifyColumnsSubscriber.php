<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TypifyColumnsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnColumnsBuildEvent::class => ['typifyColumns', 300],
        ];
    }

    public function typifyColumns(OnColumnsBuildEvent $event)
    {
        $typifiedColumns = [];

        /** @var DatasheetColumn $column */
        foreach ($event->getColumns() as $column) {

            // If its already typed column
            if (get_class($column) !== DatasheetColumn::class) {
                $typifiedColumns[] = $column;

                continue;
            }
            $type = $column->getType() ?? StringColumn::class;
            $typedColumn = new $type($column->getName());

            foreach (['position', 'title', 'width', 'align'] as $parameters) {
                if (!is_null($column->{'get' . $parameters}())) {
                    $typedColumn->{'set' . $parameters}($column->{'get' . $parameters}());
                }
            }
            $typifiedColumns[] = $typedColumn;
        }

        $event->setColumns($typifiedColumns);
    }
}
