<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use Doctrine\ORM\QueryBuilder;

class ArrayPaginationFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof ArrayDataReader && $filter instanceof PaginationFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var PaginationFilter $filter */
        $data = $dataReader->getSource();
        $data = array_splice($data, $filter->getPage() * $filter->getLimit(), $filter->getLimit());
        $dataReader->setSource($data);
    }
}