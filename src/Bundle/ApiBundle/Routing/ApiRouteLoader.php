<?php

namespace A2Global\A2Platform\Bundle\ApiBundle\Routing;

use A2Global\A2Platform\Bundle\ApiBundle\Controller\RestApiController;
use A2Global\A2Platform\Bundle\ApiBundle\Handler\ApiRequestHandlerInterface;
use A2Global\A2Platform\Bundle\ApiBundle\Registry\ApiRequestHandlerRegistry;
use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Bundle\FrameworkBundle\Routing\RouteLoaderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ApiRouteLoader implements RouteLoaderInterface
{
    public function __construct(
        protected EntityHelper              $entityHelper,
        protected ApiRequestHandlerRegistry $apiRouteProviderRegistry,
    ) {
    }

    public function __invoke(): RouteCollection
    {
        $routes = new RouteCollection();
        $entities = $this->entityHelper->getEntityList();
        $entities = array_filter($entities, function ($entityClassName) {
            return str_starts_with($entityClassName, 'App\\Entity\\');
        });


        foreach ($entities as $entity) {
            $entityName = StringUtility::normalize(StringUtility::getShortClassName($entity));

            /** @var ApiRequestHandlerInterface $apiRouteProvider */
            foreach ($this->apiRouteProviderRegistry->get() as $apiRouteProvider) {
                $route = $apiRouteProvider->getRoute($entityName);
                $routeName = $apiRouteProvider->getRouteName($entityName);
                $route->addDefaults([
                    '_controller' => [RestApiController::class, 'handleAction'],
//                    '_controller' => RestApiController::class . '::' . 'handleAction',
                ]);
                $routes->add(
                    $routeName,
                    $route,
                );
            }
        }

        return $routes;
    }

    protected function getEntityRoutes($entity)
    {
        $routes = [];
        $basename = StringUtility::getShortClassName($entity);
        $basenameCamelCase = StringUtility::toCamelCase($basename);

        /**
         * Get entity list
         *
         * GET /api/user
         */
        $route = new Route(
            sprintf('/api/%s', $basenameCamelCase),
            ['_controller' => 'A2Global\A2Platform\Bundle\ApiBundle\Controller\RestApiController::listAction']
        );
        $name = sprintf('api_%s_index', $basenameCamelCase);
        $routes[$name] = $route;

        /**
         * Get entity
         *
         * GET /api/user/1
         */
        $route = new Route(
            sprintf('/api/%s/{id}', $basenameCamelCase),
            ['_controller' => 'A2Global\A2Platform\Bundle\ApiBundle\Controller\RestApiController::showAction'],
            [
                'parameter' => '\d+',
            ],
        );
        $name = sprintf('api_%s_show', $basenameCamelCase);
        $routes[$name] = $route;

        return $routes;
    }
}