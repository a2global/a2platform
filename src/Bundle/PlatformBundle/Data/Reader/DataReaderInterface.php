<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Data\DataCollection;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\DataFilterInterface;

interface DataReaderInterface
{
    public function supports($data): bool;

    public function setSource(mixed $source): self;

    public function getSource(): mixed;

    public function addFilter(DataFilterInterface $filter);

    public function getAllFilters(): iterable;

    public function getFields(): array;

    public function readData(): DataCollection;
}