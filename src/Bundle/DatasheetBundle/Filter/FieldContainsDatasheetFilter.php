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
use Iterator;
use Symfony\Component\HttpFoundation\ParameterBag;

class FieldContainsDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'contains';
    const PARAMETER_QUERY = 'query';

    public function getName()
    {
        return static::NAME;
    }

    public function supports(DatasheetExposed $datasheet, ?DatasheetColumnInterface $column = null): bool
    {
        if (is_null($column)) {
            return false;
        }

        return in_array(get_class($column), [
            NumberColumn::class,
            StringColumn::class,
            TextColumn::class,
        ]);
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return !empty($parameters->get(self::PARAMETER_QUERY));
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        return new FieldContainsFilter($columnName, $parameters->get(self::PARAMETER_QUERY));
    }

    public function getForm(ParameterBag $parameters): Iterator
    {
        yield [
            'name' => self::PARAMETER_QUERY,
            'value' => $parameters->get(self::PARAMETER_QUERY),
            'type' => 'text',
        ];
    }
}