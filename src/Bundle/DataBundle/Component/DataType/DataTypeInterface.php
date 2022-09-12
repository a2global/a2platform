<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

interface DataTypeInterface
{
    public static function getReadablePreview($value): string;
}