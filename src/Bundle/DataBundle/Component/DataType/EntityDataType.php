<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component\DataType;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;

class EntityDataType implements DataTypeInterface
{
    private const METHODS = [
        'getName',
        'getFullname',
        'getTitle',
    ];

    public static function getReadablePreview($value): string
    {
        if (method_exists($value, '__toString')) {
            return (string)$value;
        }

        foreach (self::METHODS as $method) {
            if (method_exists($value, $method)) {
                return (string)$value->$method();
            }
        }

        return sprintf(
            '%s #%s',
            StringUtility::normalize(StringUtility::getShortClassName($value)),
            $value->getId()
        );
    }
}