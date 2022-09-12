<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

abstract class AbstractDataReader
{
//    public function getSource(): mixed
//    {
//        return $this->source;
//    }

    public function setSource($source): DataReaderInterface
    {
        $this->source = $source;

        return $this;
    }
}