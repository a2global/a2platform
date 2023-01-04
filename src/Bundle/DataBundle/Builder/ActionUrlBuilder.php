<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\DataBundle\Component\Action;
use Exception;
use Symfony\Component\Routing\RouterInterface;

class ActionUrlBuilder
{
    public function __construct(
        protected RouterInterface $router,
    ) {
    }

    public function build(Action $action, object $object = null): string
    {
        $parameters = [];
        $compiledRoute = $this->router->getRouteCollection()->get($action->getRouteName())->compile();

        foreach ($compiledRoute->getPathVariables() as $pathVariable) {
            if (isset($action->getRouteParameters()[$pathVariable])) {
                $parameters[$pathVariable] = $action->getRouteParameters()[$pathVariable];

                continue;
            }

            if ($object && $value = ObjectHelper::getProperty($object, $pathVariable)) {
                $parameters[$pathVariable] = $value;

                continue;
            }

            throw new Exception('Not enough parameters to build URL');
        }

        return $this->router->generate($action->getRouteName(), $parameters);
    }
}