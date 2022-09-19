<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;

class EntityDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'many_to_one',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return ObjectHelper::getReadableTitle($value);
    }
}