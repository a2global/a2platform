<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;

class EntityDataType implements DataTypeInterface
{
    public static function getReadablePreview($value): string
    {
        return ObjectHelper::getReadableTitle($value);
    }
}