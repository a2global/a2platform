<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class Datasheet
{
    protected array $config = [];

    public function __construct(mixed $dataSource)
    {
        $backtrace = debug_backtrace();
        $this->config['invokedAt'] = sprintf('%s:%s', $backtrace[1]['class'], $backtrace[1]['line']);
        $this->config['dataSource'] = $dataSource;
    }

    public function __invoke(): array
    {
        return $this->config;
    }

    public function setId(string $uniqueId): self
    {
        $this->config['id'] = $uniqueId;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->config['title'] = $title;

        return $this;
    }

    public function addColumn(DatasheetColumn $column): DatasheetColumn
    {
        $this->config['columns']['add'][] = $column;

        return $column;
    }

    public function getColumn($fieldName): DatasheetColumn
    {
        $column = new DatasheetColumn($fieldName);
        $this->config['columns']['update'][$fieldName] = $column;

        return $column;
    }

    public function hideColumns(...$names): self
    {
        $this->config['columns']['hide'] = $names;

        return $this;
    }

    public function showColumns(...$names): self
    {
        $this->config['columns']['show'] = $names;

        return $this;
    }
}