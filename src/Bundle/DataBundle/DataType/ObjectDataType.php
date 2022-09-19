<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

class ObjectDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'object',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        if (is_scalar($value)) {
            return (string)$value;
        }

        return json_encode($value);
    }

}