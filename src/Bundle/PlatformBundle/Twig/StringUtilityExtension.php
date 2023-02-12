<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig;

use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringUtilityExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('normalize', [$this, 'normalize']),
            new TwigFilter('urlize', [$this, 'urlize']),
            new TwigFilter('toCamelCase', [$this, 'toCamelCase']),
            new TwigFilter('toSnakeCase', [$this, 'toSnakeCase']),
            new TwigFilter('toPascalCase', [$this, 'toPascalCase']),
            new TwigFilter('removeEmoji', [$this, 'removeEmoji']),
            new TwigFilter('formatDateSimple', [$this, 'formatDateSimple']),
        ];
    }

    public function normalize($input)
    {
        return StringUtility::toReadable($input);
    }

    public function urlize($input)
    {
        return StringUtility::urlize($input);
    }

    public function toCamelCase($input)
    {
        return StringUtility::toCamelCase($input);
    }

    public function toSnakeCase($input)
    {
        return StringUtility::toSnakeCase($input);
    }

    public function toPascalCase($input)
    {
        return StringUtility::toPascalCase($input);
    }

    public function removeEmoji($text)
    {
        return StringUtility::removeEmoji($text);
    }

    public function formatDateSimple(DateTimeInterface $datetime, $format, $hoursShift = 0): string
    {
        return $datetime->modify('+' . $hoursShift . ' hours')->format($format);
    }
}