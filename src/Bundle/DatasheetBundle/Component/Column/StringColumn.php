<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\DataType\StringType;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class StringColumn extends DatasheetColumn implements DatasheetColumnInterface
{
    private const OBJECT_VALUE_STRING = 'Object';

    protected ?int $width = 200;

    protected ?bool $filterable = true;

    public function getView(DataItem $dataItem): ?string
    {
        $value = $dataItem->getValue($this->getName());

        if (is_scalar($value)) {
            return self::substring(htmlspecialchars((string)$value));
        }

        if (is_null($value)) {
            return '';
        }

        return self::OBJECT_VALUE_STRING;
    }

    public static function supportsDataType($type): bool
    {
        return in_array($type, [
            StringType::class,
        ]);
    }
}