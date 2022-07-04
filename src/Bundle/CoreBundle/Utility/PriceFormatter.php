<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use A2Global\CRM\CoreBundle\Settings\Settings;

class PriceFormatter
{
    const PRICE_FORMAT_CONFIG_NAME = 'price format';
    const CONFIG_DECIMALS_COUNT = 'decimalsCount';
    const CONFIG_THOUSAND_SEPARATOR = 'thousandSeparator';
    const CONFIG_DECIMALS_SEPARATOR = 'decimalsSeparator';
    const CONFIG_CURRENCY_SYMBOL = 'currencySymbol';
    const CONFIG_SEPARATE_BY_SPACE = 'separateBySpace';
    const CONFIG_SHOW_ZERO_DECIMALS = 'showZeroDecimals';

    private $settings;
    private $config;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function format(float $price): string
    {
        $decimalsCount = $this->getConfig()[self::CONFIG_SHOW_ZERO_DECIMALS] == false && ($price == (int)$price)
            ? 0
            : $this->getConfig()[self::CONFIG_DECIMALS_COUNT];

        $price = number_format(
            $price,
            $decimalsCount,
            $this->getConfig()[self::CONFIG_DECIMALS_SEPARATOR],
            $this->getConfig()[self::CONFIG_THOUSAND_SEPARATOR]
        );

        return sprintf(
            '%s%s%s',
            $price,
            $this->getConfig()[self::CONFIG_SEPARATE_BY_SPACE] ? ' ' : '',
            $this->getConfig()[self::CONFIG_CURRENCY_SYMBOL]
        );
    }

    public function getConfig(): array
    {
        if (is_null($this->config)) {
            $this->config = json_decode(
                $this->settings->get('Core', self::PRICE_FORMAT_CONFIG_NAME),
                true
            );
        }

        return $this->config;
    }
}