<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Builder\DatasheetFilterFormBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Builder\DatasheetViewBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use Symfony\Component\Form\FormView;
use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DatasheetTwigExtension extends AbstractExtension
{
    public function __construct(
        protected DatasheetBuilder           $datasheetBuilder,
        protected DatasheetViewBuilder       $datasheetViewBuilder,
        protected DatasheetFilterFormBuilder $filterFormBuilder,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('datasheet', [$this, 'buildDatasheet'], ['is_safe' => ['html']]),
            new TwigFunction('datasheet_cell', [$this, 'buildDatasheetCell'], ['is_safe' => ['html']]),
            new TwigFunction('datasheet_column_header', [$this, 'buildDatasheetColumnHeader'], ['is_safe' => ['html']]),
            new TwigFunction('datasheet_filters_form', [$this, 'getDatasheetFiltersForm'], ['is_safe' => ['html']]),
//            new TwigFunction('datasheet_cell_action_url', [$this, 'getDatasheetCellActionUrl']),
//            new TwigFunction('available_datasheet_filters', [$this, 'getAvailableDatasheetFilters']),
//            new TwigFunction('view_datasheet_filter', [$this, 'viewDatasheetFilter']),
        ];
    }

    public function buildDatasheet(Datasheet $datasheet): string
    {
        try {
            return $this->datasheetViewBuilder->buildDatasheet(
                $this->datasheetBuilder->buildDatasheet($datasheet)
            );
        } catch (Throwable $exception) {
            return implode('', [
                '<div class="alert alert-danger">',
                'Failed to build datasheet: ' . $exception->getMessage() . '<br>',
                'on ' . $exception->getFile() . ':' . $exception->getLine(),
                '</div>',
            ]);
        }
    }

    public function buildDatasheetColumnHeader(DatasheetColumn $column): string
    {
        return $this->datasheetViewBuilder->buildDatasheetColumnHeader($column);
    }

    public function buildDatasheetCell(DatasheetColumn $column, DataItem $dataItem): string
    {
        return $this->datasheetViewBuilder->buildDatasheetCell($column, $dataItem);
    }

    public function getDatasheetFiltersForm(DatasheetExposed $datasheet): FormView
    {
        return $this->filterFormBuilder
            ->buildDatasheetFilterForm($datasheet)
            ->createView();
    }

//    public function getDatasheetCellActionUrl(DataItem $dataItem, DatasheetColumn $column): string
//    {
//        if (!$column->getActionRouteName()) {
//            return false;
//        }
//        $params = $column->getActionRouteParams();
//
//        if ($id = $dataItem->getValue('id')) {
//            $params['id'] = $id;
//        }
//
//        return $this->router->generate($column->getActionRouteName(), $params);
//    }
//
//    public function getDatasheetFilters(DatasheetColumn $column): Iterator
//    {
//        /** @var DatasheetFilterInterface $filter */
//        foreach ($this->datasheetFilterRegistry->get() as $filter) {
//            if ($filter->supportsColumn($column)) {
//                yield $filter;
//            }
//        }
//    }
//
//    public function getDatasheetFilterFormFields(DatasheetExposed $datasheet): Iterator
//    {
//        /** @var DatasheetFilterInterface $filter */
//        foreach ($this->datasheetFilterRegistry->get() as $filter) {
//            if ($filter->supportsDatasheet($datasheet)) {
//                yield $filter;
//            }
//        }
//    }
}