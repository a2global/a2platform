<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class ActiveMenuItemMarker
{
    public function __construct(
        protected RequestStack    $requestStack,
        protected RouterInterface $router,
    ) {
    }

    public function process(Menu $menu)
    {
        $activeRouteName = $this->requestStack->getMainRequest()->attributes->get('_route');

        foreach ($menu->getItems() as $menuItem) {
            $menuItemRouteName = $menuItem->getRouteName();//$this->router->generate(, $menuItem->getRouteParameters());

            if ($activeRouteName === $menuItemRouteName) {
                $menuItem->setIsActive(true);
            }
        }
    }
}