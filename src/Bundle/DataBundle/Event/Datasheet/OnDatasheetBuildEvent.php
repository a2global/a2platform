<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;

class OnDatasheetBuildEvent
{
    const NAME = 'data.datasheet.on_build';

    protected DataReaderInterface $dataReader;

    public function __construct(
        protected DatasheetExposed $datasheet
    ) {
    }

    public function getDatasheet(): DatasheetExposed
    {
        return $this->datasheet;
    }

    public function getDataReader(): DataReaderInterface
    {
        return $this->dataReader;
    }

    public function setDataReader(DataReaderInterface $dataReader): self
    {
        $this->dataReader = $dataReader;
        return $this;
    }
}