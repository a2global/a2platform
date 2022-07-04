<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderDataReader extends AbstractDataReader implements DataReaderInterface
{
    public function supports($source): bool
    {
        return $source instanceof QueryBuilder;
    }

    public function getData(): DataCollection
    {
        /** @var QueryBuilder $originalQueryBuilder */
        $queryBuilder = $this->data;
        $this->addFieldContainsFilters($queryBuilder);
        $queryBuilder->setMaxResults(50);

        $collection = new DataCollection($this->getFields($queryBuilder));

        foreach ($this->data->getQuery()->getResult() as $object) {
            $collection->addItem(new DataItem($object));
        }

        return $collection;
    }

    protected function addFieldContainsFilters(QueryBuilder $queryBuilder)
    {
        foreach ($this->getFilters() as $filter) {
            if (!$filter instanceof FieldContainsFilter) {
                continue;
            }
            $queryBuilder
                ->andWhere(
                    sprintf(
                        '%s.%s LIKE :filterFieldContainsValue%s',
                        QueryBuilderUtility::getPrimaryAlias($queryBuilder),
                        $filter->getFieldName(),
                        ucfirst($filter->getFieldName())
                    )
                )
                ->setParameter(
                    sprintf('filterFieldContainsValue%s', ucfirst($filter->getFieldName())),
                    '%' . $filter->getContainsValue() . '%'
                );
        }
    }

    protected function getFields(QueryBuilder $queryBuilder): array
    {
        return array_map(function ($field) {
            return $field['name'];
        }, QueryBuilderUtility::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder)));
    }
}