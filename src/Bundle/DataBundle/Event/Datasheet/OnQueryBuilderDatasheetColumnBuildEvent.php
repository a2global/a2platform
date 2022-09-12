<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;

class OnQueryBuilderDatasheetColumnBuildEvent
{
    const NAME = 'data.datasheet.query_builder.column.on_build';

    protected ?DatasheetColumn $column = null;

    public function __construct(
        protected string           $fieldName,
        protected string           $fieldType,
        protected DatasheetExposed $datasheet,
    ) {
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    public function getDatasheet(): DatasheetExposed
    {
        return $this->datasheet;
    }

    public function getColumn(): ?DatasheetColumn
    {
        return $this->column;
    }

    public function setColumn(?DatasheetColumn $column): self
    {
        $this->column = $column;
        return $this;
    }
}