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
        protected ActiveMenuItemMarker     $activeMenuItemMarker,
    ) {
    }

    public function getSingleEntityMenu($objectOrClassName): Menu
    {
        $className = is_object($objectOrClassName) ? get_class($objectOrClassName) : $objectOrClassName;
        $menu = new Menu();
        $event = new EntityMenuBuildEvent($menu, $className);

        /**
         * Common menu
         * a2platform.menu.build.entity.single
         */
        $this->eventDispatcher->dispatch($event, sprintf('%s.single', EntityMenuBuildEvent::NAME));

        /**
         * Entity-specific menu
         * a2platform.menu.build.entity.single.app_entity_person
         */
        $this->eventDispatcher->dispatch($event, sprintf(
            '%s.single.%s',
            EntityMenuBuildEvent::NAME,
            StringUtility::toSnakeCase($className),
        ));

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

        $this->activeMenuItemMarker->process($menu);

        return $menu;
    }
}