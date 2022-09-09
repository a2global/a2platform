<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Builder\DatasheetViewBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DatasheetTwigExtension extends AbstractExtension
{
    public function __construct(
        protected DatasheetBuilder $datasheetBuilder,
        protected DatasheetViewBuilder $datasheetViewBuilder,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('datasheet', [$this, 'buildDatasheet'], ['is_safe' => ['html']]),
//            new TwigFunction('datasheet_cell', [$this, 'getDatasheetCell'], ['is_safe' => ['html']]),
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
//
//    public function getDatasheetCell(DataItem $dataItem, DatasheetColumn $column): string
//    {
//        return $column->getView($dataItem) ?? '';
//    }
//
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