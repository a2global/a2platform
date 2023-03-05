<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\MenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class EntityMenuBuilder
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected ActiveMenuItemMarker     $activeMenuItemMarker,
    ) {
    }

    public function getSingleEntityMenu($objectOrClassName): Menu
    {
        $className = is_object($objectOrClassName) ? get_class($objectOrClassName) : $objectOrClassName;
        $menu = new Menu();
        $event = new EntityMenuBuildEvent($menu, $className);
        $eventNames = [
            sprintf('%s.entity.single', MenuBuildEvent::NAME),
            sprintf('%s.entity.single.%s', MenuBuildEvent::NAME, StringUtility::toSnakeCase($className)),
            MenuBuildEvent::NAME,
        ];

        foreach ($eventNames as $eventName) {
            $this->eventDispatcher->dispatch($event, $eventName);
        }

        if (is_object($objectOrClassName)) {
            foreach ($menu->getItems() as $menuItem) {
                $menuItem->setRouteParameters(
                    array_merge(
                        $menuItem->getRouteParameters(),
                        ['id' => $objectOrClassName->getId()]
                    )
                );
            }
        }

        return $menu;
    }
}