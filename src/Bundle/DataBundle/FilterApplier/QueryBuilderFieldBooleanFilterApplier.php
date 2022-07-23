<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldBooleanFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderFieldBooleanFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof FieldBooleanFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var FieldBooleanFilter $filter */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $fieldPath = QueryBuilderUtility::getFieldPathByName($queryBuilder, $filter->getFieldName());

        /**
         * True it 1
         * False is 0 or null
         */
        if ($filter->getQuery()) {
            $queryBuilder->andWhere(sprintf('%s = 1', $fieldPath, $filter->getFieldName()));
        } else {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq($fieldPath, 0),
                    $queryBuilder->expr()->isNull($fieldPath),
                )
            );
        }
    }
}