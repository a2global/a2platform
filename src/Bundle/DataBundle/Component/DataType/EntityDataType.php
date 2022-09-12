<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;

class EntityDataType implements DataTypeInterface
{
    public static function getReadablePreview($value): string
    {
        if (method_exists($value, '__toString')) {
            return (string)$value;
        } else {
            return sprintf(
                '%s #%s',
                StringUtility::normalize(StringUtility::getShortClassName($value)),
                $value->getId()
            );
        }
    }
}