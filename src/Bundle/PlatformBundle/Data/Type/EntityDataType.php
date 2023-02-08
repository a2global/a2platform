<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Type;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;

class EntityDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'many_to_one',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return ObjectHelper::getReadableTitle($value);
    }

    /**
     * @codeCoverageIgnore
     * todo
     */
    public static function fromRaw($value)
    {
        return StringUtility::getShortClassName($value);
    }
}