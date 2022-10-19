<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

use DateTime;

class StringDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'string',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }

    public static function fromRaw($value)
    {
        return (string) $value;
    }
}