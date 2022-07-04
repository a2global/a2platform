<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Response\Handler;

use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use Symfony\Component\HttpFoundation\Response;

interface ResponseHandlerInterface
{
    public function createResponse(ResourceRequest $request, array $data): Response;
}