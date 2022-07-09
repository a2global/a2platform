<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use Symfony\Component\HttpFoundation\ParameterBag;

class PaginationDatasheetFilter implements DatasheetFilterInterface
{
    const PAGE_PARAMETER = 'page';
    const PER_PAGE_PARAMETER = 'per_page';

    public function supports(ParameterBag $query): bool
    {
        return true;
    }

    public function getDataFilter(ParameterBag $query): FilterInterface
    {
        $page = $query->get(self::PAGE_PARAMETER, 1);
        $page = max($page, 1) - 1;

        $perPage = $query->get(self::PER_PAGE_PARAMETER, 20);
        $perPage = max($perPage, 1);

        return new PaginationFilter($page, $perPage);
    }
}