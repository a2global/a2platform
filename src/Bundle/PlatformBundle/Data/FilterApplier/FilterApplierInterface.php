<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\FilterApplier;

use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\DataReaderInterface;

interface FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter): bool;

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter);
}