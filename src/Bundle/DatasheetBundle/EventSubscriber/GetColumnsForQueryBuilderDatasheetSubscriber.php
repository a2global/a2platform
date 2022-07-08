<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\ColumnProvider;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GetColumnsForQueryBuilderDatasheetSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnColumnsBuildEvent::class => ['getColumnsForQueryBuilderDatasheet', 500],
        ];
    }

    public function __construct(
        protected ColumnProvider $columnProvider
    ) {
    }

    public function getColumnsForQueryBuilderDatasheet(OnColumnsBuildEvent $event)
    {
        if (!$event->getDatasheet()->getConfig()['dataSource'] instanceof QueryBuilder) {
            return;
        }
        $columns = [];
        $fields = QueryBuilderUtility::getEntityFields(
            QueryBuilderUtility::getPrimaryClass($event->getDatasheet()->getConfig()['dataSource'])
        );

        foreach ($fields as $field) {
            $columns[$field['name']] = $this->findSupportedColumn($field);
        }
        $event->setColumns($columns);
    }

    protected function findSupportedColumn($field)
    {
        if (!$field['typeResolved']) {
            throw new Exception('Unresolved data type: ' . $field['type']);
        }

        foreach ($this->columnProvider->get() as $column) {
            if ($column::supportsDataType($field['typeResolved'])) {
                return new $column($field['name']);
            }
        }

        throw new Exception('Unsupported data type: ' . $field['typeResolved']);
    }
}
