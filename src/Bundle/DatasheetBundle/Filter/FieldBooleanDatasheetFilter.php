<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldBooleanFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\BooleanColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Iterator;
use Symfony\Component\HttpFoundation\ParameterBag;

class FieldBooleanDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'boolean';
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
            BooleanColumn::class,
        ]);
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return '' !== $parameters->get(self::PARAMETER_QUERY);
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        return new FieldBooleanFilter($columnName, (bool)$parameters->get(self::PARAMETER_QUERY));
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