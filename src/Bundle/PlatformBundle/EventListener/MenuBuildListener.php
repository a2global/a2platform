<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener;

use A2Global\A2Platform\Bundle\PlatformBundle\Event\MenuBuildEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsEventListener(event: 'a2platform.menu.build', method: 'setActive', priority: -990)]
class MenuBuildListener
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public function setActive(MenuBuildEvent $event)
    {
        $activeRouteName = $this->requestStack->getMainRequest()->attributes->get('_route');

        foreach ($event->getMenu()->getItems() as $menuItem) {
            $menuItemRouteName = $menuItem->getRouteName();//$this->router->generate(, $menuItem->getRouteParameters());

            if ($activeRouteName === $menuItemRouteName) {
                $menuItem->setIsActive(true);
            }
        }
    }
}
