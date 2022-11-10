<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Event;

use Knp\Menu\MenuItem;

class AdminMenuBuildEvent
{
    public function __construct(
        MenuItem $menu
    ) {
    }
}