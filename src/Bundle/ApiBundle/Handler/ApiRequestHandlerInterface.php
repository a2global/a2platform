<?php

namespace A2Global\A2Platform\Bundle\ApiBundle\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

interface ApiRequestHandlerInterface
{
    public function getRouteNamePrefix(): string;

    public function getRouteName(string $itemName): string;

    public function getRoute(string $itemName): Route;

    public function handleRequest(Request $request);
}