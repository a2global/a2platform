<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

class IntegerDataType implements DataTypeInterface
{
    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }

}