<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Registry;

class DataReaderRegistry
{
    public function __construct(
        protected $dataReaders
    ) {
    }

    public function get()
    {
        return $this->dataReaders;
    }
}