<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Type;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('a2platform.data.type')]
interface DataTypeInterface
{
    public static function supportsByOrmType($type): bool;

    public static function getReadablePreview($value): string;

    public static function fromRaw($value);
}