<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig;

use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\TranslationHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StringTwigExtension extends AbstractExtension
{
    public function __construct(
        protected TranslationHelper $translationHelper,
    ) {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('translate', [$this, 'translate']),
            new TwigFilter('readable', [$this, 'readable']),
            new TwigFilter('readableTitle', [$this, 'getReadableTitle']),
            new TwigFilter('urlize', [$this, 'urlize']),
            new TwigFilter('toCamelCase', [$this, 'toCamelCase']),
            new TwigFilter('toSnakeCase', [$this, 'toSnakeCase']),
            new TwigFilter('toPascalCase', [$this, 'toPascalCase']),
            new TwigFilter('removeEmoji', [$this, 'removeEmoji']),
            new TwigFilter('formatDateSimple', [$this, 'formatDateSimple']),
            new TwigFilter('className', [$this, 'getClassName']),
            new TwigFilter('shortClassName', [$this, 'getShortClassName']),
        ];
    }

    public function readable($input)
    {
        return StringUtility::toReadable($input);
    }

    public function getReadableTitle($object, bool $entityNameWithIdByDefault = true): ?string
    {
        return EntityHelper::getReadableTitle($object, $entityNameWithIdByDefault);
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

    public function translate($originalString): string
    {
        return $this->translationHelper->translate($originalString);
    }

    public function getClassName($object): string
    {
        return get_class($object);
    }

    public function getShortClassName($object): string
    {
        return StringUtility::getShortClassName($object);
    }
}