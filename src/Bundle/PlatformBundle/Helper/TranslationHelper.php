<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Helper;

use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationHelper
{
    public function __construct(
        protected TranslatorInterface $translator,
    ) {
    }

    public function translate(string $originalString): string
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