<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;

interface FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter, string $fieldName = null): bool;

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter, string $fieldName = null);
}