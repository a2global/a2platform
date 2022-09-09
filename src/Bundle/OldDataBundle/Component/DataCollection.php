<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

/**
 * Class DataCollection
 * @package A2Global\A2Platform\Bundle\DataBundle\Component
 * todo: figure out if this can be replaced by DoctrineCollection/PersistentCollection etc
 */
class DataCollection
{
    protected ?array $items = [];

    protected ?int $itemsTotal = null;

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