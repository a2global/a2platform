<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\ColumnProvider;
use Doctrine\ORM\Query\Expr\Select;
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
        $queryBuilder = $event->getDatasheet()->getConfig()['dataSource'];

        if (!$queryBuilder instanceof QueryBuilder) {
            return;
        }
        $columns = [];
        $entityFields = QueryBuilderUtility::getEntityFields(
            QueryBuilderUtility::getPrimaryClass($event->getDatasheet()->getConfig()['dataSource'])
        );
        $selects = $queryBuilder->getDQLPart('select');
        $primaryAlias = QueryBuilderUtility::getPrimaryAlias($queryBuilder);

        /** @var Select $select */
        foreach ($selects as $select) {
            $parts = $select->getParts();
            $part = reset($parts);
            $tmp = explode('.', $part);

            if (count($tmp) !== 2 || $tmp[0] !== $primaryAlias) {
                throw new DatasheetBuildException('Unsupported QueryBuilder select: ' . $part);
            }
            $fieldName = $tmp[1];
            $field = $this->getFieldByName($fieldName, $entityFields);
            $columns[] = $this->findSupportedColumn($field);
        }
        $event->setColumns($columns);
    }

    protected function getFieldByName($fieldName, $fields)
    {
        foreach ($fields as $field) {
            if($field['name'] === $fieldName){
                return $field;
            }
        }
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
