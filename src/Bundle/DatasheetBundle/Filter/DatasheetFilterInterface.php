<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

interface DatasheetFilterInterface
{
    public function supports(ParameterBag $query): bool;

    public function getDataFilter(ParameterBag $query): FilterInterface;
}