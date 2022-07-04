<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class TextColumn extends DatasheetColumn
{
    public function getView(DataItem $dataItem): string
    {
        $value = (string)$dataItem->getValue($this->getName());

        return $value;
    }
}