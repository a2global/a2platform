<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Event;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;

class OnAfterBuildEvent
{
    public const NAME = 'a2platform.datasheet.build.on_after_build';

    public function __construct(
        protected DatasheetExposed $datasheet
    ) {
    }

    public function getDatasheet(): DatasheetExposed
    {
        return $this->datasheet;
    }
}