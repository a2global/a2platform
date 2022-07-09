<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Provider;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\DatasheetFilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Registry\DatasheetFilterRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

class FilterProvider
{
    public function __construct(
        protected RequestStack $requestStack,
        protected DatasheetFilterRegistry $filterRegistry,
    ) {
    }

    /**
     * @param $datasheetId
     * @return FilterInterface[]
     */
    public function getFilters($datasheetId): array
    {
        $filters = [];
        $query = self::decapsulate($this->requestStack->getCurrentRequest()->query, $datasheetId) ?? [];

        /** @var DatasheetFilterInterface $filter */
        foreach ($this->filterRegistry->get() as $filter) {
            if ($filter->supports($query)) {
                $filters[] = $filter->getDataFilter($query);
            }
        }

        return $filters;
    }

    public static function encapsulate(string $var, string $datasheetId): string
    {
        return sprintf('%s[%s]', self::getCapsuleName($datasheetId), $var);
    }

    public static function decapsulate(ParameterBag $bag, string $datasheetId): ParameterBag
    {
        return new ParameterBag($bag->get(sprintf('%s', self::getCapsuleName($datasheetId)), []));
    }

    public static function getCapsuleName(string $datasheetId): string
    {
        return sprintf('ds%s', $datasheetId);
    }
}