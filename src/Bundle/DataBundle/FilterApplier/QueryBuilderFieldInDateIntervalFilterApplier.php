<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldDateIntervalFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldInDateIntervalFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderFieldInDateIntervalFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof FieldInDateIntervalFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var FieldInDateIntervalFilter $filter */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $fieldPath = sprintf('%s.%s', QueryBuilderUtility::getPrimaryAlias($queryBuilder), $filter->getFieldName());
        $parameters = [
            'from' => sprintf('%sInDateIntervalFrom', $filter->getFieldName()),
            'to' => sprintf('%sInDateIntervalTo', $filter->getFieldName()),
        ];
        $queryBuilder->andWhere(
            $queryBuilder->expr()->between(
                $fieldPath,
                sprintf(':%s', $parameters['from']),
                sprintf(':%s', $parameters['to']),
            )
        );
        $queryBuilder->setParameter($parameters['from'], $filter->getDateFrom());
        $queryBuilder->setParameter($parameters['to'], $filter->getDateTo());
    }
}