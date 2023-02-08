<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\MenuItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\MenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: 'a2platform.menu.build.admin_main', method: 'preAdminMainMenuBuild', priority: 900)]
#[AsEventListener(event: 'a2platform.menu.build.admin_main', method: 'entitiesAdminMainMenuBuild', priority: 100)]
#[AsEventListener(event: 'a2platform.menu.build.admin_main', method: 'postAdminMainMenuBuild', priority: -900)]
class AdminMenuEventListener
{
    public function __construct(
        protected RouterInterface $router,
        protected EntityHelper    $entityHelper,
    ) {
    }

    public function preAdminMainMenuBuild(MenuBuildEvent $event): void
    {
        $menuItem = (new MenuItem('Homepage'))
            ->setUrl('/');
        $event->getMenu()->addItem($menuItem);

        $menuItem = (new MenuItem('Admin'))
            ->setRouteName('admin_default');
        $event->getMenu()->addItem($menuItem);
    }

    public function entitiesAdminMainMenuBuild(MenuBuildEvent $event): void
    {
        $entityList = $this->entityHelper->getEntityList();
        sort($entityList);

        foreach ($entityList as $entityClassName) {
            $entityName = StringUtility::toReadable(StringUtility::getShortClassName($entityClassName));
            $menuItem = (new MenuItem(StringUtility::toReadable($entityName)))
                ->setRouteName('admin_entity_list')
                ->setRouteParameters([
                    'className' => $entityClassName,
                ]);
            $event->getMenu()->addItem($menuItem);
        }
    }

    public function postAdminMainMenuBuild(MenuBuildEvent $event): void
    {
        $menuItem = (new MenuItem('Sign out'))
            ->setRouteName('app_logout');
        $event->getMenu()->addItem($menuItem);
    }
}
