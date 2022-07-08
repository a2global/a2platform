<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Provider;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\BooleanColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\CustomColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DateTimeColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\ObjectColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\TagsColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\TextColumn;

class ColumnProvider
{
    public const COLUMN_TYPES = [
        BooleanColumn::class,
        CustomColumn::class,
        DateTimeColumn::class,
        NumberColumn::class,
        StringColumn::class,
        TextColumn::class,
        TagsColumn::class,
        ObjectColumn::class,
    ];

    /**
     * @return DatasheetColumnInterface[]
     */
    public function get(): array
    {
        return self::COLUMN_TYPES;
    }
}