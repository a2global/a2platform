<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber\OnColumnBuild;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
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
        $queryBuilder = $event->getDatasheet()->getConfig()['dataSource'];

        if (!$queryBuilder instanceof QueryBuilder) {
            return;
        }

        if (count($queryBuilder->getDQLPart('select')) !== 1) {
            throw new DatasheetBuildException('Not supports custom dql for now');
        }
        $fields = QueryBuilderUtility::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder));
        $datasheetColumns = [];

        foreach ($fields as $field) {
            $datasheetColumns[$field['name']] = $this->findSupportedColumn(
                $field['name'],
                $field['type'],
                $field['typeResolved'],
            );
        }
        $event->setColumns($datasheetColumns);
    }

    protected function findSupportedColumn($name, $type, $typeResolved)
    {
        if (!$typeResolved) {
            throw new Exception('Unresolved data type: ' . $type);
        }

        foreach ($this->columnProvider->get() as $column) {
            if ($column::supportsDataType($typeResolved)) {
                return new $column($name);
            }
        }

        throw new Exception('Unsupported data type: ' . $typeResolved);
    }
}
