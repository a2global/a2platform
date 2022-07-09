<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

class DataCollection
{
    protected ?array $items = [];

    protected ?int $total = null;

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

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;
        return $this;
    }
}