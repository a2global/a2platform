<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;


class Datasheet// implements DatasheetInterface
{
    protected string $invokedAt;

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
//    public function getColumn($fieldName): DatasheetColumn
//    {
//        $column = new DatasheetColumn($fieldName);
//        $this->config['columns']['update'][$fieldName] = $column;
//
//        return $column;
//    }
//
//    public function hideColumns(...$names): self
//    {
//        $this->config['columns']['hide'] = $names;
//
//        return $this;
//    }
//
//    public function showColumns(...$names): self
//    {
//        $this->config['columns']['show'] = $names;
//
//        return $this;
//    }
}