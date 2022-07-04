<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

class DataCollection
{
    protected array $items;

    public function __construct(
        protected array $fields
    ) {
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addItem(DataItem $item)
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}