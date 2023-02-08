<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Type;

interface DataTypeInterface
{
    public static function supportsByOrmType($type): bool;
    public static function getReadablePreview($value): string;
    public static function fromRaw($value);
}