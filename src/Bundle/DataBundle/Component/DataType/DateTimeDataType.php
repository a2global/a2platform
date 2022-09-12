<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

use DateTime;

class DateTimeDataType implements DataTypeInterface
{
    public static function getReadablePreview($value): string
    {
        /** @var DateTime $value */
        return $value ? $value->format('Y-m-d h:i:s') : '';
    }
}