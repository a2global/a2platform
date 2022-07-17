<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldDateIntervalFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldInDateIntervalFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\SearchFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DateTimeColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\TextColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use DateTime;
use Iterator;
use Symfony\Component\HttpFoundation\ParameterBag;

class FieldInDateIntervalDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'in_date_interval';
    const PARAMETER_DATE_FROM = 'from';
    const PARAMETER_DATE_TO = 'to';

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
        return !empty($parameters->get(self::PARAMETER_DATE_FROM))
            && !empty($parameters->get(self::PARAMETER_DATE_TO));
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        return new FieldInDateIntervalFilter(
            $columnName,
            new DateTime($parameters->get(self::PARAMETER_DATE_FROM)),
            new DateTime($parameters->get(self::PARAMETER_DATE_TO)),
        );
    }

    public function getForm(ParameterBag $parameters): Iterator
    {
        yield [
            'name' => self::PARAMETER_DATE_FROM,
            'value' => $parameters->get(self::PARAMETER_DATE_FROM),
            'type' => 'text',
        ];

        yield [
            'name' => self::PARAMETER_DATE_TO,
            'value' => $parameters->get(self::PARAMETER_DATE_TO),
            'type' => 'text',
        ];
    }
}