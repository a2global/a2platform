<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber\OnColumnBuild;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\ColumnProvider;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GetColumnsForQueryBuilderDatasheetSubscriber implements EventSubscriberInterface
{
    protected $entityFieldsCache = [];

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

    /**
     * Target is to get all fields from QueryBuilder
     * and return typified DatasheetColumns
     *
     * @param OnColumnsBuildEvent $event
     * @throws DatasheetBuildException
     */
    public function getColumnsForQueryBuilderDatasheet(OnColumnsBuildEvent $event)
    {
        $datasheetColumns = [];
        $queryBuilder = $event->getDatasheet()->getConfig()['dataSource'];

        if (!$queryBuilder instanceof QueryBuilder) {
            return;
        }
        $selects = $queryBuilder->getDQLPart('select');

        foreach ($selects as $select) {
            $column = $this->getColumnForQBSelect($queryBuilder, $select);
            $datasheetColumns[$column->getName()] = $column;
        }
        $event->setColumns($datasheetColumns);
    }

    protected function getColumnForQBSelect(QueryBuilder $queryBuilder, $select): DatasheetColumnInterface
    {
        $parts = $select->getParts();
        $fieldPath = reset($parts);
        $fieldPathParts = explode('.', $fieldPath);
        $entityAlias = $fieldPathParts[0];
        $fieldName = $fieldPathParts[1];
        $className = QueryBuilderUtility::getClassNameByAlias($queryBuilder, $entityAlias);
        $fieldInfo = QueryBuilderUtility::getEntityFields($className, $fieldName);

        return $this->findSupportedColumn($fieldName, $fieldInfo);
    }

    protected function findSupportedColumn($columnName, $fieldInfo)
    {
        if (!$fieldInfo['typeResolved']) {
            throw new Exception('Unresolved data type: ' . $fieldInfo['type']);
        }

        foreach ($this->columnProvider->get() as $column) {
            if ($column::supportsDataType($fieldInfo['typeResolved'])) {
                return new $column($columnName);
            }
        }

        throw new Exception('Unsupported data type: ' . $fieldInfo['typeResolved']);
    }
}
