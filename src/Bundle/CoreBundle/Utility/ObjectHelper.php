<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use Exception;

class ObjectHelper
{
    static $identityFields = [
        'name',
        'fullname',
        'title',
    ];

    public static function getProperty(mixed $object, string $propertyName)
    {
        foreach (['', 'get', 'is', 'has'] as $prefix) {
            $method = $prefix . StringUtility::toPascalCase($propertyName);

            if (method_exists($object, $method)) {
                return $object->{$method}();
            }
        }

        throw new Exception(
            sprintf(
                'Failed to get data %s from %s via get/is/has+%s',
                $propertyName,
                get_class($object),
                StringUtility::toPascalCase($object)
            )
        );
    }

    public static function setProperty(mixed $object, string $propertyName, mixed $propertyValue)
    {
        foreach (['', 'set'] as $prefix) {
            $method = $prefix . StringUtility::toPascalCase($propertyName);

            if (method_exists($object, $method)) {
                return $object->{$method}($propertyValue);
            }
        }

        throw new Exception(
            sprintf(
                'Failed to set data %s to %s via set+%s',
                $propertyName,
                get_class($object),
                StringUtility::toPascalCase($method)
            )
        );
    }

    public static function getReadableTitle(mixed $object, string $nullValue = '')
    {
        if(!$object){
            return $nullValue;
        }

        if (method_exists($object, '__toString')) {
            return (string)$object;
        }

        foreach (static::$identityFields as $field) {
            $method = 'get' . $field;

            if (method_exists($object, $method)) {
                return (string)$object->$method();
            }
        }

        return sprintf(
            '%s #%s',
            StringUtility::normalize(StringUtility::getShortClassName($object)),
            $object->getId()
        );
    }
}