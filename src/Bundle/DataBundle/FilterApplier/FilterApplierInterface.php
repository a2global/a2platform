<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;

interface FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter): bool;

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter);
}