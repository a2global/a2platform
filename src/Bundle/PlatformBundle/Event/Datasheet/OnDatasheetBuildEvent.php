<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet;


use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetExposed;

class OnDatasheetBuildEvent
{
    const NAME = 'data.datasheet.build';

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