<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\MenuItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\MenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MenuBuilder
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function build(string $name): Menu
    {
        $event = new MenuBuildEvent(new Menu());
        $this->eventDispatcher->dispatch(
            $event,
            sprintf('%s.%s', MenuBuildEvent::NAME, StringUtility::toSnakeCase($name)),
        );
        $this->eventDispatcher->dispatch($event);

        return $event->getMenu();
    }

    public static function getDefault(Menu $menu): ?MenuItem
    {
        foreach ($menu->getItems() as $menuItem) {
            if ($menuItem->isDefault()) {
                return $menuItem;
            }
        }

        return $menu->getItems()[0] ?? null;
    }
}