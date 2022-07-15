<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\DatasheetFilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Registry\DatasheetFilterRegistry;
use Iterator;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DatasheetTwigExtension extends AbstractExtension
{
    public function __construct(
        protected DatasheetBuilder $datasheetBuilder,
        protected Environment $twig,
        protected DatasheetFilterRegistry $datasheetFilterRegistry,
        protected RequestStack $requestStack,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('datasheet', [$this, 'getDatasheet'], ['is_safe' => ['html']]),
            new TwigFunction('datasheet_cell', [$this, 'getDatasheetCell'], ['is_safe' => ['html']]),
            new TwigFunction('available_datasheet_filters', [$this, 'getAvailableDatasheetFilters']),
//            new TwigFunction('available_datasheet_column_filters', [$this, 'getDatasheetColumnFilters']),
            new TwigFunction('view_datasheet_filter', [$this, 'viewDatasheetFilter']),
        ];
    }

    public function getDatasheet(Datasheet $datasheet): string
    {
        try {
            $datasheet = $this->datasheetBuilder->build($datasheet);

            return $this->twig->render('@Datasheet/datasheet.html.twig', [
                'datasheet' => $datasheet,
            ]);
        } catch (Throwable $exception) {
            return implode('', [
                '<div class="alert alert-danger">',
                'Failed to build datasheet: ' . $exception->getMessage() . '<br>',
                'on ' . $exception->getFile() . ':' . $exception->getLine(),
                '</div>',
            ]);
        }
    }

    public function getDatasheetCell(DataItem $dataItem, DatasheetColumn $column): string
    {
        return $column->getView($dataItem) ?? '';
    }

    public function getDatasheetFilters(DatasheetColumn $column): Iterator
    {
        /** @var DatasheetFilterInterface $filter */
        foreach ($this->datasheetFilterRegistry->get() as $filter) {
            if ($filter->supportsColumn($column)) {
                yield $filter;
            }
        }
    }

    public function getDatasheetFilterFormFields(DatasheetExposed $datasheet): Iterator
    {
        /** @var DatasheetFilterInterface $filter */
        foreach ($this->datasheetFilterRegistry->get() as $filter) {
            if ($filter->supportsDatasheet($datasheet)) {
                yield $filter;
            }
        }
    }

    public function viewDatasheetFilter(
        DatasheetExposed $datasheet,
        DatasheetColumn $column,
        DatasheetFilterInterface $filter
    ) {
    }
}