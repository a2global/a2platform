<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Provider;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\BooleanColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DateTimeColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\EntityColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\ObjectColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\TextColumn;

class ColumnProvider
{
    // todo make this dynamic
    public const COLUMN_TYPES = [
        BooleanColumn::class,
        DateTimeColumn::class,
        NumberColumn::class,
        StringColumn::class,
        TextColumn::class,
        ObjectColumn::class,
        EntityColumn::class,
    ];

    /**
     * @return DatasheetColumnInterface[]
     */
    public function get(): array
    {
        return self::COLUMN_TYPES;
    }
}