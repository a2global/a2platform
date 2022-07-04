<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use DateTimeInterface;
use IntlDateFormatter;

class DateTimeUtility
{
    public static function asLocalDateString(DateTimeInterface $datetime, string $locale): string
    {
        $formatter = new IntlDateFormatter(
            $locale === 'ua' ? 'uk' : $locale,
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );

        return $formatter->format($datetime);
    }
}