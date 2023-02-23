<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\MenuItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: 'a2platform.menu.build.entity.single', method: 'singleEntityMenuBuild', priority: 900)]
class EntityMenuBuildEventListener
{
    public function __construct(
        protected RouterInterface $router,
        protected EntityHelper    $entityHelper,
    ) {
    }

    public function singleEntityMenuBuild(EntityMenuBuildEvent $event): void
    {
        $menuItem = (new MenuItem('View'))
            ->setRouteName('admin_entity_view')
            ->setRouteParameters([
                'className' => $event->getClassName(),
            ]);
        $event->getMenu()->addItem($menuItem);

        $menuItem = (new MenuItem('Edit'))
            ->setRouteName('admin_entity_edit')
            ->setRouteParameters([
                'className' => $event->getClassName(),
            ]);
        $event->getMenu()->addItem($menuItem);
    }
}
