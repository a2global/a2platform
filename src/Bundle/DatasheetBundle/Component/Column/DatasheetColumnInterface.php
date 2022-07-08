<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\DataType\DataTypeInterface;

interface DatasheetColumnInterface
{
    public static function supportsDataType($type): bool;
}