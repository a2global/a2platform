<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;

class OnDatasheetColumnFiltersBuildEvent
{
    const NAME = 'data.datasheet.column.filter.build';

    public function __construct(
        protected DatasheetExposed $datasheet,
        protected DatasheetColumn  $column,
        protected DataReaderInterface $dataReader,
    ) {
    }

    public function getDatasheet(): DatasheetExposed
    {
        return $this->datasheet;
    }

    public function getColumn(): DatasheetColumn
    {
        return $this->column;
    }

    public function getDataReader(): DataReaderInterface
    {
        return $this->dataReader;
    }
}