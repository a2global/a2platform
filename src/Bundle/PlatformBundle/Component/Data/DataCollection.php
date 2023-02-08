<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Component\Data;

class DataCollection
{
    protected ?int $itemsTotal = null;

    public function __construct(
        protected array $fields,
        protected array $items = [],
    ) {
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(DataItem $item)
    {
        $this->items[] = $item;
    }

    public function getItemsTotal(): ?int
    {
        return $this->itemsTotal;
    }

    public function setItemsTotal(?int $itemsTotal): self
    {
        $this->itemsTotal = $itemsTotal;
        return $this;
    }
}