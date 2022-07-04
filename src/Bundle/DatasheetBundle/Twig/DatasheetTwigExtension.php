<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetViewBuilder;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DatasheetTwigExtension extends AbstractExtension
{
    public function __construct(
        protected DatasheetBuilder $datasheetBuilder,
        protected DatasheetViewBuilder $datasheetViewBuilder,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('datasheet', [$this, 'getDatasheet'], ['is_safe' => ['html']]),
            new TwigFunction('datasheet_cell', [$this, 'getDatasheetCell'], ['is_safe' => ['html']]),
        ];
    }

    public function getDatasheet(Datasheet $datasheet): string
    {
        return $this->datasheetViewBuilder->getDatasheetView(
            $this->datasheetBuilder->build($datasheet)
        );
    }

    public function getDatasheetCell(DataItem $dataItem, DatasheetColumn $column): string
    {
        return $this->datasheetViewBuilder
            ->getDatasheetCellView($dataItem, $column);
    }
}