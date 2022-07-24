<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsDateFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DateTimeColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use DateTime;
use Iterator;
use Symfony\Component\HttpFoundation\ParameterBag;

class FieldEqualsDateDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'equals_date';
    const PARAMETER_DATE = 'date';

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
            DateTimeColumn::class,
        ]);
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return !empty($parameters->get(self::PARAMETER_DATE));
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        return new FieldEqualsDateFilter($columnName, new DateTime($parameters->get(self::PARAMETER_DATE)));
    }

    public function getForm(ParameterBag $parameters): Iterator
    {
        yield [
            'name' => self::PARAMETER_DATE,
            'value' => $parameters->get(self::PARAMETER_DATE),
            'type' => 'text',
        ];
    }
}