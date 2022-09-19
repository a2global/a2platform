<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

class BooleanDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'boolean',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }
}