<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Handler\Response;

use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ResponseHandlerInterface
{
    public function supports(Request $request, $isAdmin = false): bool;

    public function createResponse(ResourceRequest $request, array $data): Response;
}