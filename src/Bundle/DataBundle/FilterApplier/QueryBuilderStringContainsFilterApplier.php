<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\StringContainsDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderStringContainsFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null): bool
    {
        return
            $dataReader instanceof QueryBuilderDataReader
            && $filter instanceof StringContainsDataFilter
            && !empty($fieldName);
    }

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null)
    {
        /** @var StringContainsDataFilter $filter */
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $fieldPath = QueryBuilderUtility::getFieldPathByName($queryBuilder, $fieldName);
        $queryBuilder
            ->andWhere(sprintf('%s LIKE :%sContains', $fieldPath, $fieldName))
            ->setParameter(sprintf('%sContains', $fieldName), sprintf('%%%s%%', $filter->getValue()));
    }
}