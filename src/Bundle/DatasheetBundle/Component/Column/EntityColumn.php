<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\DataType\EntityType;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class EntityColumn extends DatasheetColumn implements DatasheetColumnInterface
{
    protected ?bool $filterable = false;

    public function getView(DataItem $dataItem): ?string
    {
        $value = $dataItem->getValue($this->getName());

        if (method_exists($value, '__toString')) {
            $text = (string)$value;
        } else {
            $text = sprintf(
                '%s #%s',
                StringUtility::normalize(StringUtility::getShortClassName($value)),
                $value->getId()
            );
        }
        $url = '/admin/data/view/' . get_class($value) . '/' . $value->getId();

        return sprintf(
            '<a href="%s" class="text-bold text-primary">%s</a>',
            $url,
            self::substring($text)
        );
    }

    public static function supportsDataType($type): bool
    {
        return in_array($type, [
            EntityType::class,
        ]);
    }
}