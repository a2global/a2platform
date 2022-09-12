<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use Twig\Environment;

class DatasheetViewBuilder
{
    public function __construct(
        protected Environment $twig,
    ) {
    }

    public function buildDatasheet(DatasheetExposed $datasheet)
    {
        return $this->twig->render('@Data/datasheet/layout.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    public function buildDatasheetCell(DatasheetColumn $column, DataItem $dataItem)
    {
        return $this->twig->render('@Data/datasheet/cell.html.twig', [
            'value' => $column->getReadableView($dataItem),
        ]);
    }
}