<?php

namespace A2Global\A2Platform\Bundle\ApiBundle\Controller;

use A2Global\A2Platform\Bundle\ApiBundle\Handler\ApiRequestHandlerInterface;
use A2Global\A2Platform\Bundle\ApiBundle\Registry\ApiRequestHandlerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class RestApiController extends AbstractController
{
    public function handleAction(Request $request)
    {
        /** @var ApiRequestHandlerInterface $apiRequestHandler */
        foreach ($this->get(ApiRequestHandlerRegistry::class)->get() as $apiRequestHandler) {
            if (str_starts_with($request->attributes->get('_route'), $apiRequestHandler->getRouteNamePrefix())) {
                return $this->createResponse($apiRequestHandler->handleRequest($request));
            }
        }

        throw new NotFoundHttpException();
    }

    public function createResponse(array $data): Response
    {
        $response = new JsonResponse(
            $this->get(SerializerInterface::class)->normalize($data, null, ['groups' => '*'])
        );
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ApiRequestHandlerRegistry::class,
            SerializerInterface::class,
        ]);
    }
}