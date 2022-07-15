<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Symfony\Component\HttpFoundation\ParameterBag;
use Twig\Environment;

class EqualsDatasheetFilter// extends AbstractDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'equals';
    const FILTER_CLASS = EqualsFilter::class;

    public function isDefined(ParameterBag $parameters): bool
    {
        return $parameters->get('type') === self::NAME && $parameters->get('value');
    }

    public function get(ParameterBag $parameters): FilterInterface
    {
        return new EqualsFilter($parameters->get('field'), $parameters->get('value'));
    }

    public function supportsColumn(DatasheetColumn $column): bool
    {
        return in_array($column::class, [
            NumberColumn::class,
            StringColumn::class,
        ]);
    }
}