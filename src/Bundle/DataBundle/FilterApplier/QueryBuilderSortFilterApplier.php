<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\SortDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderSortFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null): bool
    {
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof SortDataFilter;
    }

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null)
    {
        /** @var SortDataFilter $filter */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $queryBuilder
            ->resetDQLPart('orderBy')
            ->addOrderBy(
                sprintf('%s.%s', QueryBuilderUtility::getPrimaryAlias($queryBuilder), $filter->getFieldName()),
                $filter->getType() === SortDataFilter::TYPE_DESCENDING ? 'DESC' : 'ASC'
            );
    }
}