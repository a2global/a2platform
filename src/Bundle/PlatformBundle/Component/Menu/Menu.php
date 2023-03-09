<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu;

class Menu
{
    /** @var MenuItem[] */
    protected array $items = [];

    public function addItem(MenuItem $item)
    {
        $this->items[$item->getName()] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function removeItems(...$names): self
    {
        foreach ($names as $name) {
            unset($this->items[$name]);
        }

        return $this;
    }
}