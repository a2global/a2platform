<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Routing;

use A2Global\A2Platform\Bundle\CoreBundle\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CoreRouteLoader implements RouteLoaderInterface
{
    public function __construct()
    {
    }

    public function __invoke(): RouteCollection
    {
        $routes = new RouteCollection();

        $routes->add('app_default', new Route('', [
            '_controller' => [DefaultController::class, 'defaultAction']
        ]));

        return $routes;
    }
}