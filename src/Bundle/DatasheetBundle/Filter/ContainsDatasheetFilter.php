<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\TextColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Symfony\Component\HttpFoundation\ParameterBag;

class ContainsDatasheetFilter extends AbstractDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'contains';
    const FILTER_CLASS = ContainsFilter::class;

    public function get(ParameterBag $parameters): FilterInterface
    {
        return new ContainsFilter($parameters->get('field'), $parameters->get('value'));
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return $parameters->get('type') === self::NAME && $parameters->get('value');
    }

    public function supportsColumn(DatasheetColumn $column): bool
    {
        return in_array($column::class, [
            StringColumn::class,
        ]);
    }
}