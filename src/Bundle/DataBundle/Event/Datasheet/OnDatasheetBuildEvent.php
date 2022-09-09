<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;

class OnDatasheetBuildEvent
{
    protected DataCollection $dataCollection;

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

    public function getDataCollection(): DataCollection
    {
        return $this->dataCollection;
    }

    public function setDataCollection(DataCollection $dataCollection): self
    {
        $this->dataCollection = $dataCollection;
        return $this;
    }
}