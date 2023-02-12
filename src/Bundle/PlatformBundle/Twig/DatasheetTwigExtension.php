<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Datasheet\DatasheetBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Datasheet\DatasheetFilterFormBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Datasheet\DatasheetViewBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Data\DataItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\Datasheet;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetColumn;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetExposed;
use A2Global\A2Platform\Bundle\PlatformBundle\Manager\DatasheetParametersManager;
use Symfony\Component\Form\FormView;
use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DatasheetTwigExtension extends AbstractExtension
{
    public function __construct(
        protected DatasheetBuilder           $datasheetBuilder,
        protected DatasheetFilterFormBuilder $filterFormBuilder,
        protected DatasheetViewBuilder       $datasheetViewBuilder,
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
            new TwigFunction('datasheet_filters_form', [$this, 'getDatasheetFiltersForm'], ['is_safe' => ['html']]),
            new TwigFunction('datasheet_column_filters_form', [$this, 'getDatasheetColumnFiltersForm'], ['is_safe' => ['html']]),
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
                '<details><summary>on ' . $exception->getFile() . ':' . $exception->getLine() . ' &darr;</summary>',
                $exception->getTraceAsString() . '</details>',
                '</div>',
            ]);
        }
    }

    public function buildDatasheetCell(DatasheetExposed $datasheet, DatasheetColumn $column, DataItem $dataItem): string
    {
        return $this->datasheetViewBuilder->buildDatasheetCell($datasheet, $column, $dataItem);
    }

    public function getDatasheetFiltersForm(DatasheetExposed $datasheet): FormView
    {
        return $this->filterFormBuilder
            ->buildDatasheetFilterForm($datasheet)
            ->createView();
    }

    public function getDatasheetColumnFiltersForm(
        FormView         $formView,
        DatasheetExposed $datasheet,
        DatasheetColumn  $column,
    ) {
        $columnFiltersForm = $formView
            ->offsetGet($datasheet->getId())
            ->offsetGet(DatasheetParametersManager::DATASHEET_COLUMN_FILTERS_CONTAINER);

        if ($columnFiltersForm->offsetExists($column->getName())) {
            return $columnFiltersForm->offsetGet($column->getName());
        }
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