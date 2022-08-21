<?php

namespace A2Global\A2Platform\Bundle\ApiBundle\Handler;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

class GetRequestHandler extends AbstractApiRequestHandler implements ApiRequestHandlerInterface
{
    public function getRouteNamePrefix(): string
    {
        return 'api_get_';
    }

    public function getRouteName(string $itemName): string
    {
        return sprintf('%s%s', $this->getRouteNamePrefix(), StringUtility::toSnakeCase($itemName));
    }

    public function getRoute(string $itemName): Route
    {
        return new Route(
            sprintf('/api/%s/{id}', StringUtility::toSnakeCase(StringUtility::pluralize($itemName))),
            [],
            [
                'id' => '\d+',
            ]
        );
    }

    public function handleRequest(Request $request): array
    {
        $repository = $this->getRepository($request);

        return [
            $repository->find($request->attributes->get('_route_params')['id']),
        ];
    }
}