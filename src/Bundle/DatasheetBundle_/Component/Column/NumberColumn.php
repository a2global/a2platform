<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DecimalType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\FloatType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\IntegerType;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class NumberColumn extends DatasheetColumn implements DatasheetColumnInterface
{
    protected ?string $align = DatasheetColumn::ALIGN_RIGHT;

    protected ?int $width = 100;

    protected ?bool $filterable = true;

    public function getView(DataItem $dataItem): ?string
    {
        $value = $dataItem->getValue($this->getName());

        return $value ?? '';
    }

    public static function supportsDataType($type): bool
    {
        return in_array($type, [
            IntegerType::class,
            FloatType::class,
            DecimalType::class,
        ]);
    }
}