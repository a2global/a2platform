<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Event;

use Knp\Menu\MenuItem;

class EntityMenuBuildEvent
{
    public function __construct(
        protected MenuItem $menu,
        protected object   $object,
    ) {
    }

    public function getMenu(): MenuItem
    {
        return $this->menu;
    }

    public function getObject(): object
    {
        return $this->object;
    }

    public function getEntityClassname(): string
    {
        return get_class($this->object);
    }
}