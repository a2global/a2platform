<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use Twig\Environment;

class DatasheetViewBuilder
{
    private const TEXT_LIMIT = 20;

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

    public function buildDatasheetColumnHeader(DatasheetColumn $column)
    {
        return $this->twig->render('@Data/datasheet/column_header.html.twig', [
            'name' => $column->getName(),
            'text' => $column->getTitle() ?? StringUtility::normalize($column->getName()),
            'width' => $column->getWidth(),
            'align' => $column->getAlign(),
        ]);
    }

    public function buildDatasheetCell(DatasheetColumn $column, DataItem $dataItem)
    {
        $text = $column->getReadableView($dataItem);

        if (mb_strlen($text) > self::TEXT_LIMIT) {
            $text = trim(mb_substr($text, 0, self::TEXT_LIMIT)) . '…';
        }

        return $this->twig->render('@Data/datasheet/cell.html.twig', [
            'text' => $text,
            'align' => $column->getAlign(),
        ]);
    }
}