<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu;

class Menu
{
    /** @var MenuItem[] */
    protected array $items = [];

    public function addItem(MenuItem $item)
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}