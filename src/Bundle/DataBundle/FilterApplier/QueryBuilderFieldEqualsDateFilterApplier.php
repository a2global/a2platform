<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderFieldEqualsDateFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof FieldEqualsDateFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var FieldEqualsDateFilter $filter */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $fieldPath = QueryBuilderUtility::getFieldPathByName($queryBuilder, $filter->getFieldName());
        $queryBuilder
            ->andWhere(sprintf('DATE(%s) = :%sEqualsDate', $fieldPath, $filter->getFieldName()))
            ->setParameter(sprintf('%sEqualsDate', $filter->getFieldName()), $filter->getDate()->format('y-m-d'));
    }
}