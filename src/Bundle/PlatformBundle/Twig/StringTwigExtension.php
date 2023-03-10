<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig;

use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use DateTimeInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use function PHPUnit\Framework\stringContains;

class StringTwigExtension extends AbstractExtension
{
    public function __construct(
        protected TranslatorInterface $translator
    ) {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('translate', [$this, 'translate']),
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

    public function translate($originalString): string
    {
        $translated = $this->translator->trans($originalString);

        if ($translated != $originalString) {
            return $translated;
        }
        $hasPath = str_contains($originalString, '.');

        if ($hasPath) {
            $tmp = explode('.', $originalString);
            $simpleString = $tmp[count($tmp) - 1];
        } else {
            $simpleString = $originalString;
        }
        $simpleString = StringUtility::toReadable($simpleString);
        $translated = $this->translator->trans($simpleString);

        if ($translated != $simpleString) {
            return $translated;
        }
        $typicalString = sprintf('typical_translation.%s', StringUtility::toSnakeCase($simpleString));
        $translated = $this->translator->trans($typicalString);

        if ($translated != $typicalString) {
            return $translated;
        }

        return $simpleString;
    }
}