<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\FilterApplier;

use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\PaginationDataFilter;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\QueryBuilderDataReader;
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