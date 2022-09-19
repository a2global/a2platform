<?php

namespace A2Global\A2Platform\Bundle\DataBundle\DataType;

use DateTime;

class DateTimeDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'datetime',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        /** @var DateTime $value */
        return $value ? $value->format('Y-m-d h:i:s') : '';
    }
}