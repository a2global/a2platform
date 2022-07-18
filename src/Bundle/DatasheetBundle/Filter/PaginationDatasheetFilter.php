<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Iterator;
use Symfony\Component\HttpFoundation\ParameterBag;

class PaginationDatasheetFilter implements DatasheetFilterInterface
{
    const NAME = 'pagination';

    const PARAMETER_PAGE = 'page';
    const PARAMETER_LIMIT = 'limit';

    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 20;

    public function getName()
    {
        return static::NAME;
    }

    public function supports(DatasheetExposed $datasheet, ?DatasheetColumnInterface $column = null): bool
    {
        return is_null($column);
    }

    public function isDefined(ParameterBag $parameters): bool
    {
        return true;
    }

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null)
    {
        $page = $parameters->get(self::PARAMETER_PAGE, self::DEFAULT_PAGE);
        $page = max($page, 1) - 1;

        $limit = $parameters->get(self::PARAMETER_LIMIT, self::DEFAULT_LIMIT);
        $limit = max($limit, 1);

        return new PaginationFilter($page, $limit);
    }

    public function getForm(ParameterBag $parameters): Iterator
    {
        yield [
            'name' => self::PARAMETER_PAGE,
            'value' => $parameters->get(self::PARAMETER_PAGE, self::DEFAULT_PAGE),
            'type' => 'text',
        ];

        yield [
            'name' => self::PARAMETER_LIMIT,
            'value' => $parameters->get(self::PARAMETER_LIMIT, self::DEFAULT_LIMIT),
            'type' => 'text',
        ];
    }
}