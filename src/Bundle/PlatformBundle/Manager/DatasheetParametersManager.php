<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Manager;

use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetColumn;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetExposed;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DatasheetParametersManager
{
    public const DATASHEET_FILTERS_CONTAINER = 'df';
    public const DATASHEET_COLUMN_FILTERS_CONTAINER = 'cf';

    public function __construct(
        protected FormFactoryInterface $formFactory,
        protected RequestStack         $requestStack,
    ) {
    }

    public function addEmptyFiltersForm(DatasheetExposed $datasheet)
    {
        $builder = $this->formFactory
            ->createNamedBuilder($datasheet->getId())
            ->add(self::DATASHEET_FILTERS_CONTAINER, null, ['compound' => true])
            ->add(self::DATASHEET_COLUMN_FILTERS_CONTAINER, null, ['compound' => true]);
        $datasheet->setFilterFormBuilder($builder);
    }

    public function addFilterToDatasheet(DatasheetExposed $datasheet, DataFilterInterface $dataFilter)
    {
        $container = $datasheet->getFilterFormBuilder()
            ->get(self::DATASHEET_FILTERS_CONTAINER)
            ->add($dataFilter->getName(), null, ['compound' => true,])
            ->get($dataFilter->getName());
        $dataFilter->buildForm($container);
    }

    public function addFilterToDatasheetColumn(
        DatasheetExposed    $datasheet,
        DatasheetColumn     $column,
        DataFilterInterface $dataFilter
    ) {
        if (!$datasheet->getFilterFormBuilder()->get(self::DATASHEET_COLUMN_FILTERS_CONTAINER)->has($column->getName())) {
            $datasheet->getFilterFormBuilder()
                ->get(self::DATASHEET_COLUMN_FILTERS_CONTAINER)
                ->add($column->getName(), null, ['compound' => true]);
        }

        $container = $datasheet->getFilterFormBuilder()
            ->get(self::DATASHEET_COLUMN_FILTERS_CONTAINER)
            ->get($column->getName())
            ->add($dataFilter->getName(), null, ['compound' => true,])
            ->get($dataFilter->getName());
        $dataFilter->buildForm($container);
    }

    public function getDatasheetFilterParameters(DatasheetExposed $datasheet, string $name, string $columnName = null)
    {
        $datasheetParameters = $this->requestStack
            ->getMainRequest()
            ->get('datasheet', [])[$datasheet->getId()] ?? [];

        if ($columnName) {
            $filterParameters = $datasheetParameters[self::DATASHEET_COLUMN_FILTERS_CONTAINER][$columnName][$name] ?? [];
        } else {
            $filterParameters = $datasheetParameters[self::DATASHEET_FILTERS_CONTAINER][$name] ?? [];
        }

        return $filterParameters;
    }

    public function applyParameters(DataFilterInterface $filter, array $parameters): DataFilterInterface
    {
        foreach ($parameters as $name => $value) {
            $setter = 'set' . $name;

            if (method_exists($filter, $setter)) {
                $filter->$setter($value);
            }
        }

        return $filter;
    }

}