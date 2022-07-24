<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

interface DatasheetColumnInterface
{
    public static function supportsDataType($type): bool;
}