<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\DataType\FloatType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\IntegerType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\ObjectType;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class ObjectColumn extends DatasheetColumn implements DatasheetColumnInterface
{
    protected ?bool $filterable = false;

    public function getView(DataItem $dataItem): ?string
    {
        return 'object';
    }

    public static function supportsDataType($type): bool
    {
        return in_array($type, [
            ObjectType::class,
        ]);
    }
}