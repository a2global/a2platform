<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter;

class BooleanDataFilter extends AbstractDataFilter implements DataFilterInterface
{
    const NAME = 'boolean';

    public function isEnabled(): bool
    {
        return $this->value === '0' || $this->value === '1';
    }
}