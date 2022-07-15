<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\SearchFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderSearchFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof SearchFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var SearchFilter $filter */
        echo 'search everywhere for ' . $filter->getQuery();
//        /** @var QueryBuilder $queryBuilder */
//        $queryBuilder = $dataReader->getSource();
//        $fieldPath = sprintf('%s.%s', QueryBuilderUtility::getPrimaryAlias($queryBuilder), $filter->getFieldName());
//        $queryBuilder
//            ->andWhere(sprintf('%s LIKE :%sContains', $fieldPath, $filter->getFieldName()))
//            ->setParameter(sprintf('%sContains', $filter->getFieldName()), sprintf('%%%s%%', $filter->getValue()));
    }
}