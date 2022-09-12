<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateTimeType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateType;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use DateTime;

class DateTimeColumn extends DatasheetColumn implements DatasheetColumnInterface
{
    public function getView(DataItem $dataItem): ?string
    {
        /** @var DateTime $value */
        $value = $dataItem->getValue($this->getName());

        return $value ? $value->format('Y-m-d h:i:s') : '';
    }

    public static function supportsDataType($type): bool
    {
        return in_array($type, [
            DateType::class,
            DateTimeType::class,
        ]);
    }
}