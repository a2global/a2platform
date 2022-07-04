<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Twig\Environment;

class DatasheetViewBuilder
{
    protected $cellViewMapping = [];

    public function __construct(
        protected Environment $twig,
    ) {
    }

    public function getDatasheetView(DatasheetExposed $datasheet): string
    {
        return $this->twig->render('@Datasheet/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    public function getDatasheetCellView(DataItem $dataItem, DatasheetColumn $column): string
    {
        return $column->getView($dataItem);
    }
}