<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;

class ArrayPaginationFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter): bool
    {
        return $dataReader instanceof ArrayDataReader && $filter instanceof PaginationDataFilter;
    }

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter)
    {
        /** @var PaginationDataFilter $filter */
        $data = $dataReader->getSource();
        $data = array_splice($data, $filter->getPage() * $filter->getLimit(), $filter->getLimit());
        $dataReader->setSource($data);
    }
}