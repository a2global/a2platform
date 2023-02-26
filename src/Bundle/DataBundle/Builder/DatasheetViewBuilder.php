<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class DatasheetViewBuilder
{
    private const TEXT_LIMIT = 20;
    private const EMPTY_LINK_TEXT_PLACEHOLDER = '<i>empty</i>';

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

    public function buildDatasheetCell(DatasheetExposed $datasheet, DatasheetColumn $column, DataItem $dataItem)
    {
        $text = trim($column->getReadableView($dataItem));

        if (mb_strlen($text) > self::TEXT_LIMIT) {
            $text = trim(mb_substr($text, 0, self::TEXT_LIMIT)) . '…';
        }
        $link = $column->getLink();

        if ($link) {
            $link = $this->router->generate($link[0], array_merge($link[1] ?? [], [
                'id' => $dataItem->getValue('id'),
            ]));
            $linkId = sprintf('%s.%s.%s', $datasheet->getId(), $column->getName(), $dataItem->getValue('id'));

            if (length($text) === 0) {
                $text = self::EMPTY_LINK_TEXT_PLACEHOLDER;
            }
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