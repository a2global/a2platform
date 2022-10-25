<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\BooleanDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderBooleanFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null): bool
    {
        return
            $dataReader instanceof QueryBuilderDataReader
            && $filter instanceof BooleanDataFilter
            && !empty($fieldName);
    }

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $fieldPath = QueryBuilderUtility::getFieldPathByName($queryBuilder, $fieldName);

        if ($filter->getValue() === '1') {
            $queryBuilder->andWhere(sprintf('%s = 1', $fieldPath));
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