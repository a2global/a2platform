<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

use DateTime;

class FloatDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'float',
            'decimal',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }

    public static function fromRaw($value)
    {
        return (float)$value;
    }
}