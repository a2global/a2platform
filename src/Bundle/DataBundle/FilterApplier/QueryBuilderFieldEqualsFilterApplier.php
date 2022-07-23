<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderFieldEqualsFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof FieldEqualsFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var FieldEqualsFilter $filter */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $fieldPath = QueryBuilderUtility::getFieldPathByName($queryBuilder, $filter->getFieldName());
        $queryBuilder
            ->andWhere(sprintf('%s = :%sEquals', $fieldPath, $filter->getFieldName()))
            ->setParameter(sprintf('%sEquals', $filter->getFieldName()), $filter->getQuery());
    }
}