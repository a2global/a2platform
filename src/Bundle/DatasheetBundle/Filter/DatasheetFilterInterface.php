<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Iterator;
use Symfony\Component\HttpFoundation\ParameterBag;

interface DatasheetFilterInterface
{
    public function supports(DatasheetExposed $datasheet, ?DatasheetColumnInterface $column = null): bool;

    public function getName();

    public function isDefined(ParameterBag $parameters): bool;

    public function getDataFilter(ParameterBag $parameters, ?string $columnName = null);

    public function getForm(ParameterBag $parameters): Iterator;
}