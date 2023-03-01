<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Routing;

use A2Global\A2Platform\Bundle\PlatformBundle\Controller\AdminEntityCrudController;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class EntityActionRouteLoader implements RouteLoaderInterface
{
    const ACTIONS = [
        'list',
        'view',
        'create',
        'update',
        'delete',
    ];

    public function __invoke(): RouteCollection
    {
        $routes = new RouteCollection();

        foreach (self::ACTIONS as $action) {
            $route = new Route(sprintf('/admin/entity/%s', $action), [
                '_controller' => [AdminEntityCrudController::class, sprintf('%sAction', $action)],
            ]);
            $routes->add(sprintf('admin_entity_%s', $action), $route);
        }

        return $routes;
    }
}