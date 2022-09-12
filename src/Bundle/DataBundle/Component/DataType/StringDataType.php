<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

class StringDataType implements DataTypeInterface
{
    public static function getReadablePreview($value): string
    {
        return 'x';//(string)$value;
    }

}