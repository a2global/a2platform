<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\SortFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;

class SortDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'sort';
    const PARAMETER_SORT_BY = 'by';
    const PARAMETER_SORT_DIRECTION = 'direction';
    const DEFAULT_SORT_BY = 'id';

    public function getName()
    {
        return static::NAME;
    }

    public function supports(DatasheetExposed $datasheet, ?DatasheetColumnInterface $column = null): bool
    {
        return $datasheet->getConfig()['dataSource'] instanceof QueryBuilder && is_null($column);
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return !empty($parameters->get(self::PARAMETER_SORT_BY));
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        $direction = $parameters->get(self::PARAMETER_SORT_DIRECTION) === SortFilter::DESCENDING
            ? SortFilter::DESCENDING
            : SortFilter::ASCENDING; // all other cases = ASC direction

        return new SortFilter($parameters->get(self::PARAMETER_SORT_BY, self::DEFAULT_SORT_BY), $direction);
    }

    public function getForm(ParameterBag $parameters)
    {
        return [
            self::PARAMETER_SORT_BY => $parameters->get(self::PARAMETER_SORT_BY, self::DEFAULT_SORT_BY),
            self::PARAMETER_SORT_DIRECTION => $parameters->get(self::PARAMETER_SORT_DIRECTION),
        ];
    }
}