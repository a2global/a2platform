<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\MenuItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\MenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: 'a2platform.menu.build.admin_sidebar', method: 'preAdminSidebarMenuBuild', priority: 900)]
#[AsEventListener(event: 'a2platform.menu.build.admin_sidebar', method: 'entitiesAdminSidebarMenuBuild', priority: 100)]
#[AsEventListener(event: 'a2platform.menu.build.admin_sidebar', method: 'postAdminSidebarMenuBuild', priority: -900)]
class AdminSidebarMenuBuildEventListener
{
    public function __construct(
        protected RouterInterface $router,
        protected EntityHelper    $entityHelper,
    ) {
    }

    public function preAdminSidebarMenuBuild(MenuBuildEvent $event): void
    {
        $menuItem = (new MenuItem('homepage'))
            ->setUrl('/');
        $event->getMenu()->addItem($menuItem);

        $menuItem = (new MenuItem('admin'))
            ->setRouteName('admin_default');
        $event->getMenu()->addItem($menuItem);
    }

    public function entitiesAdminSidebarMenuBuild(MenuBuildEvent $event): void
    {
        $entityList = $this->entityHelper->getEntityList();
        sort($entityList);

        foreach ($entityList as $entityClassName) {
            $entityName = StringUtility::toReadable(StringUtility::getShortClassName($entityClassName));
            $menuItem = (new MenuItem(StringUtility::toSnakeCase($entityName)))
                ->setRouteName('admin_entity_index')
                ->setRouteParameters([
                    'className' => $entityClassName,
                ]);
            $event->getMenu()->addItem($menuItem);
        }
    }

    public function postAdminSidebarMenuBuild(MenuBuildEvent $event): void
    {
        $menuItem = (new MenuItem('sign_out'))
            ->setRouteName('app_logout');
        $event->getMenu()->addItem($menuItem);
    }
}
