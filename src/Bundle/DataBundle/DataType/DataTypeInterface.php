<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

interface DataTypeInterface
{
    public static function supportsByOrmType($type): bool;
    public static function getReadablePreview($value): string;
}