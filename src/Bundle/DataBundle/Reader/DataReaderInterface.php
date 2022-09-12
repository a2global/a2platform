<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;

interface DataReaderInterface
{
    public function supports($data): bool;

//    public function getSource(): mixed;

    public function setSource($data): self;

    public function getData(): DataCollection;

//    public function addFilter($filter): self;
}