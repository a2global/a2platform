<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Event;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;

class MenuBuildEvent
{
    public const NAME = 'a2platform.menu.build';

    public function __construct(
       protected Menu $menu
    ) {
    }

    public function getMenu(): Menu
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu): void
    {
        $this->menu = $menu;
    }
}