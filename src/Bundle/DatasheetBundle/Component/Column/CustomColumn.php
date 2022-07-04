<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class CustomColumn extends DatasheetColumn
{
    protected $handler;

    public function setHandler(callable $function)
    {
        $this->handler = $function;
    }

    public function getView(DataItem $dataItem): string
    {
        $handler = $this->handler;

        return $handler($dataItem);
    }
}