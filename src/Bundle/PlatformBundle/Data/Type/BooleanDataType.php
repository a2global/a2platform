<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Type;

class BooleanDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'boolean',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return (string)$value;
    }

    public static function fromRaw($value)
    {
        return (bool)$value;
    }
}