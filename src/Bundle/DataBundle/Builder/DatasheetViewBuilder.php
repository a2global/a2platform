<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class DatasheetViewBuilder
{
    private const TEXT_LIMIT = 20;

    public function __construct(
        protected Environment     $twig,
        protected RouterInterface $router,
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

    public function buildDatasheetCell(DatasheetExposed $datasheet, DatasheetColumn $column, DataItem $dataItem)
    {
        $text = $column->getReadableView($dataItem);

        if (mb_strlen($text) > self::TEXT_LIMIT) {
            $text = trim(mb_substr($text, 0, self::TEXT_LIMIT)) . '…';
        }
        $link = $column->getLink();

        if ($link) {
            $link = $this->router->generate($link[0], array_merge($link[1] ?? [], [
                'id' => $dataItem->getValue('id'),
            ]));
            $linkId = sprintf('%s.%s.%s', $datasheet->getId(), $column->getName(), $dataItem->getValue('id'));
        }
        $classes = [];

        if ($column->isBold()) {
            $classes[] = 'text-bold';
        }

        return $this->twig->render('@Data/datasheet/cell.html.twig', [
            'text' => $text,
            'align' => $column->getAlign(),
            'link' => $link,
            'linkId' => $linkId ?? null,
            'classes' => implode(' ', $classes),
        ]);
    }
}