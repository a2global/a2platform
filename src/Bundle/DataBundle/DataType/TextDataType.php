<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

class TextDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'text',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }
}