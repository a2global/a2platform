<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Event;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;

class OnDataBuildEvent
{
    public const NAME = 'a2platform.datasheet.build.on_data_build';

    protected DataReaderInterface $dataReader;

    protected ?DataCollection $dataCollection = null;

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

    public function setDataReader(DataReaderInterface $dataReader): void
    {
        $this->dataReader = $dataReader;
    }

    public function getDataCollection(): ?DataCollection
    {
        return $this->dataCollection;
    }

    public function setDataCollection(DataCollection $dataCollection): void
    {
        $this->dataCollection = $dataCollection;
    }
}