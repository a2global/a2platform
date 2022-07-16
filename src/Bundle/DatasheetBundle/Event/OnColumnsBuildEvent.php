<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Event;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;

class OnColumnsBuildEvent
{
    public const NAME = 'a2platform.datasheet.build.on_columns_build';

    protected ?array $columns = null;

    public function __construct(
        protected DatasheetExposed $datasheet
    ) {
    }

    public function getDatasheet(): DatasheetExposed
    {
        return $this->datasheet;
    }

    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function getColumns(): ?array
    {
        return $this->columns;
    }
}