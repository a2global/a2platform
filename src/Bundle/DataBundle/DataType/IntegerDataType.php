<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

class IntegerDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'int',
            'integer',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }
}