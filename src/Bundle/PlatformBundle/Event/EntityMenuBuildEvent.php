<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Event;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;

class EntityMenuBuildEvent extends MenuBuildEvent
{
    public const NAME = 'a2platform.menu.build.entity';

    public function __construct(
        protected Menu   $menu,
        protected string $className,
    ) {
        parent::__construct($this->menu);
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}