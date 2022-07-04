<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class BooleanColumn extends DatasheetColumn
{
    protected ?int $width = 70;

    protected ?string $align = DatasheetColumn::ALIGN_CENTER;

    public function getView(DataItem $dataItem): string
    {
        (bool)$value = $dataItem->getValue($this->getName());

        return $value
            ? '<span href="#" class="badge badge-success" style="background-color: #007bb6">yes</span>'
            : '<span href="#" class="badge badge-secondary" style="background-color: #aaaaaa">no</span>';
    }
}