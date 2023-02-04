<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener;

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
        $event->getMenu()->addItem(
            (new MenuItem('Homepage'))->setUrl('/')
        );
        $event->getMenu()->addItem(
            (new MenuItem('Admin'))->setUrl($this->router->generate('admin_default'))
        );
    }

    public function entitiesAdminMainMenuBuild(MenuBuildEvent $event): void
    {
        $entityList = $this->entityHelper->getEntityList();
        sort($entityList);

        foreach ($entityList as $entityFqcn) {
            $entityName = StringUtility::toReadable(StringUtility::getShortClassName($entityFqcn));
            $menuItem = (new MenuItem(StringUtility::toReadable($entityName)))
                ->setUrl($this->router->generate('admin_entity_list', [
                    'fqcn' => $entityFqcn,
                ]));
            $event->getMenu()->addItem($menuItem);
        }
    }

    public function postAdminMainMenuBuild(MenuBuildEvent $event): void
    {
        $menuItem = (new MenuItem('Sign out'))
            ->setUrl($this->router->generate('app_logout'));
        $event->getMenu()->addItem($menuItem);
    }
}
