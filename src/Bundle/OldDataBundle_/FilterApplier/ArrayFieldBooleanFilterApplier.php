<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldBooleanFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use Doctrine\ORM\QueryBuilder;

class ArrayFieldBooleanFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof ArrayDataReader && $filter instanceof FieldBooleanFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var FieldBooleanFilter $filter */
        $filteredItems = [];

        foreach ($dataReader->getSource() as $item) {
            if ((bool)$item[$filter->getFieldName()] !== $filter->getQuery()) {
                continue;
            }
            $filteredItems[] = $item;
        }

        $dataReader->setSource($filteredItems);
    }
}