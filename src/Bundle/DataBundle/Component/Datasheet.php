<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetInterface;

class Datasheet implements DatasheetInterface
{
    public function __construct(
        protected mixed   $datasource,
        protected ?string $title = null,
    ) {
//        $backtrace = debug_backtrace();
//        $this->invokedAt = sprintf('%s:%s', $backtrace[1]['class'], $backtrace[1]['line']);
    }

    public function __invoke(): array
    {
        return [
            'datasource' => $this->datasource,
            'title' => $this->title,
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