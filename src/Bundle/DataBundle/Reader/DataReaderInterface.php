<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;

interface DataReaderInterface
{
    public function supports($data): bool;

    public function setSource(mixed $source): self;

    public function getSource(): mixed;

    public function addFilter(DataFilterInterface $filter);

    public function getFilters(): array;

    public function readData(): DataCollection;
}