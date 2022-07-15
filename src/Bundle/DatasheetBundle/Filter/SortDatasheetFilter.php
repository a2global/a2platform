<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Symfony\Component\HttpFoundation\ParameterBag;

class SortDatasheetFilter extends AbstractDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'sort';
    const PARAMETER_SORT_BY = 'by';
    const PARAMETER_SORT_DIRECTION = 'direction';

    public function supports(DatasheetExposed $datasheet, ?DatasheetColumnInterface $column = null): bool
    {
        return is_null($column);
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return $parameters->get(self::PARAMETER_SORT_BY);
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        // TODO: Implement get() method.
    }

    public function getForm(ParameterBag $parameters)
    {
        return [
            self::PARAMETER_SORT_BY => $parameters->get(self::PARAMETER_SORT_BY),
            self::PARAMETER_SORT_DIRECTION => $parameters->get(self::PARAMETER_SORT_DIRECTION),
        ];
    }
}