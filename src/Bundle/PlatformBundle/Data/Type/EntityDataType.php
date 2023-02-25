<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Type;

use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;

class EntityDataType implements DataTypeInterface
{
    public static function supportsByOrmType($type): bool
    {
        return in_array($type, [
            'relation',
        ]);
    }

    public static function getReadablePreview($value): string
    {
        return EntityHelper::getReadableTitle($value);
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