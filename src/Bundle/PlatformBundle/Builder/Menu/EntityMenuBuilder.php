<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class EntityMenuBuilder
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function getSingleEntityMenu(string $entityClassname): Menu
    {
        $menu = new Menu();
        $event = new EntityMenuBuildEvent($menu, $entityClassname);

        /** Common menu */
        $this->eventDispatcher->dispatch($event, sprintf('%s.single', EntityMenuBuildEvent::NAME));

        /** Entity-specific menu */
        $this->eventDispatcher->dispatch($event, sprintf(
            '%s.single.%s',
            EntityMenuBuildEvent::NAME,
            StringUtility::toSnakeCase($entityClassname),
        ));

        return $menu;
    }
}