<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Entity\Tag;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;

class TagsColumn extends DatasheetColumn
{
    protected ?int $width = 200;

    public function getView(DataItem $dataItem): string
    {
        $tags = [];

        /** @var Tag $tag */
        foreach ($dataItem->getValue('tags') as $tag) {
            $tags[] = '<a href="#" class="badge badge-info">' . $tag->getName() . '</a><br>';
        }

        return implode('', $tags);
    }
}