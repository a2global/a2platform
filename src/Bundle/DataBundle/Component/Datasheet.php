<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;


class Datasheet
{
    protected string $invokedAt;

    protected array $columnsToUpdate = [];

    protected array $controls = [];

    public function __construct(
        protected mixed   $datasource,
        protected ?string $title = null,
    ) {
        $backtrace = debug_backtrace();
        $this->invokedAt = sprintf('%s:%s', $backtrace[1]['class'], $backtrace[1]['line']);
    }

    public function __invoke(): array
    {
        return [
            'datasource' => $this->datasource,
            'title' => $this->title,
            'invokedAt' => $this->invokedAt,
            'columnsToUpdate' => $this->columnsToUpdate,
            'controls' => $this->controls,
        ];
    }
//
//    public function setActionUrl(string $urlWithIdPlaceholder): self
//    {
//        $this->config['actionUrl'] = $urlWithIdPlaceholder;
//
//        return $this;
//    }
//
//    public function addColumn(DatasheetColumn $column): DatasheetColumn
//    {
//        $this->config['columns']['add'][] = $column;
//
//        return $column;
//    }
//
    public function getColumn($fieldName): DatasheetColumn
    {
        $column = new DatasheetColumn($fieldName);
        $this->columnsToUpdate[$fieldName] = $column;

        return $column;
    }

    public function addControl($text, $url)
    {
        $this->controls[$text] = $url;
    }

//    public function hideColumns(...$names): self
//    {
//        $this->config['columns']['hide'] = $names;
//
//        return $this;
//    }

//    public function showColumns(...$names): self
//    {
//        $this->config['columns']['show'] = $names;
//
//        return $this;
//    }
}