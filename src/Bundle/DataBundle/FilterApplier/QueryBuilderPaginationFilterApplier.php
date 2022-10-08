<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderPaginationFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null): bool
    {
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof PaginationDataFilter;
    }

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null)
    {
        /** @var PaginationDataFilter $filter */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $queryBuilder
            ->setFirstResult(max($filter->getPage()-1, 0) * $filter->getLimit())
            ->setMaxResults($filter->getLimit());
    }
}