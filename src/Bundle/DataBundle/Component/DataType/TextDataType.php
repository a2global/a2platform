<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

class TextDataType implements DataTypeInterface
{
    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }
}