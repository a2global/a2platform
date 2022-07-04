<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Provider;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

class FilterProvider
{
    public function __construct(
        protected RequestStack $requestStack
    ) {
    }

    public function getFilters($datasheetId): array
    {
        $filters = [];
        $query = $this->requestStack->getCurrentRequest()->query;
        $parameters = self::decapsulate($query, $datasheetId) ?? [];

        if (isset($parameters['filter'])) {
            foreach ($parameters['filter'] as $key => $value) {
                if (is_array($value)) {
                } else {
                    if (trim($value)) {
                        $filters[] = new FieldContainsFilter($key, $value);
                    }
                }
            }
        }

        return $filters;
    }

    public static function encapsulate(string $var, string $datasheetId): string
    {
        return sprintf('%s[%s]', self::getCapsuleName($datasheetId), $var);
    }

    public static function decapsulate(ParameterBag $bag, string $datasheetId)
    {
        return $bag->get(sprintf('%s', self::getCapsuleName($datasheetId)));
    }

    public static function getCapsuleName(string $datasheetId): string
    {
        return sprintf('ds%s', $datasheetId);
    }
}