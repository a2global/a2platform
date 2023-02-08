<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Type;

use DateTime;

class DateDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'date',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        /** @var DateTime $value */
        return $value ? $value->format('Y-m-d') : '';
    }

    public static function fromRaw($value)
    {
        return new DateTime($value);
    }
}