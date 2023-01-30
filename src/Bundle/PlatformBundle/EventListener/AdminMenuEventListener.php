<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\MenuItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\MenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: 'a2platform.menu.build.admin_main', priority: 900, method: 'preAdminMainMenuBuild')]
#[AsEventListener(event: 'a2platform.menu.build.admin_main', priority: 100, method: 'entitiesAdminMainMenuBuild')]
#[AsEventListener(event: 'a2platform.menu.build.admin_main', priority: -900, method: 'postAdminMainMenuBuild')]
class AdminMenuEventListener
{
    public function __construct(
        protected RouterInterface $router,
        protected EntityHelper    $entityHelper,
    ) {
    }

    public function preAdminMainMenuBuild(MenuBuildEvent $event)
    {
        $event->getMenu()->addItem(
            (new MenuItem('Homepage'))->setUrl('/')
        );
        $event->getMenu()->addItem(
            (new MenuItem('Admin'))->setUrl($this->router->generate('admin_default'))
        );
    }

    public function entitiesAdminMainMenuBuild(MenuBuildEvent $event)
    {
        foreach ($this->entityHelper->getEntityList() as $entityFqcn){
            $entityName = StringUtility::toReadable(StringUtility::getShortClassName($entityFqcn));
            $event->getMenu()->addItem(
                (new MenuItem(StringUtility::toReadable($entityName)))
            );
        }
    }

    public function postAdminMainMenuBuild(MenuBuildEvent $event)
    {
        $event->getMenu()->addItem(
            (new MenuItem('Sign out'))->setUrl($this->router->generate('app_logout'))
        );
    }
}