<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use Symfony\Component\HttpFoundation\ParameterBag;

class PaginationDatasheetFilter// extends AbstractDatasheetFilter implements DatasheetFilterInterface
{
    const PAGE_PARAMETER = 'page';
    const PER_PAGE_PARAMETER = 'per_page';

    public function get(ParameterBag $parameters): DatasheetFilterInterface
    {
        $instance = $this->getInstanceBase($query);
        $this->buildPagesLinks($query);
    }

    public function supports(ParameterBag $parameters): bool
    {
        return true;
    }

    public function buildDataFilter(ParameterBag $query): FilterInterface
    {
        $page = $query->get(self::PAGE_PARAMETER, 1);
        $page = max($page, 1) - 1;

        $perPage = $query->get(self::PER_PAGE_PARAMETER, 20);
        $perPage = max($perPage, 1);

        return new PaginationFilter($page, $perPage);
    }

    protected function buildPagesLinks()
    {

    }
}