<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\SearchFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\TextColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Symfony\Component\HttpFoundation\ParameterBag;

class FieldContainsDatasheetFilter extends AbstractDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'contains';
    const PARAMETER_QUERY = 'query';

    public function supports(DatasheetExposed $datasheet, ?DatasheetColumnInterface $column = null): bool
    {
        return !is_null($column) && in_array(get_class($column), [
                StringColumn::class,
            ]);
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return $parameters->get(self::PARAMETER_QUERY);
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        return new FieldContainsFilter($columnName, $parameters->get(self::PARAMETER_QUERY));
    }

    public function getForm(ParameterBag $parameters)
    {
        return [
            self::PARAMETER_QUERY => $parameters->get(self::PARAMETER_QUERY),
        ];
    }
}