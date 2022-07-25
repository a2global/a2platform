<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Handler\Response;

use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseHandler implements ResponseHandlerInterface
{
    public function __construct(
        protected SerializerInterface $serializer
    ) {
    }

    public function supports(Request $request, $isAdmin = false): bool
    {
        return (stristr($request->headers->get('accept'), 'application/json') !== false);
    }

    public function createResponse(ResourceRequest $request, array $data): Response
    {
        $response = new JsonResponse([
            'data' => $this->serializer->normalize($data, null, ['groups' => 'Default']),
        ]);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }
}