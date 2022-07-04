<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Builder\ResourceRequestBuilder;
use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{
    #[Route('/show/{id}', name: '_view')]
    public function viewAction(Request $request, $id)
    {
        $this->denyDirectAccess();
        $resourceRequest = $this->get(ResourceRequestBuilder::class)
            ->build($request, ResourceRequest::ACTION_VIEW);
        $resource = $this->getDoctrine()->getRepository($resourceRequest->getSubjectClass())->find($id);

        if (!$resource) {
            throw new NotFoundHttpException('Source not found');
        }

        return $resourceRequest->getResponseHandler()->createResponse($resourceRequest, [
            'subjectName' => $resourceRequest->getSubjectName(),
            'subjectResource' => $resource,
        ]);
    }

    #[Route('/index', name: '_index')]
    public function indexAction(Request $request)
    {
        $this->denyDirectAccess();
        $resourceRequest = $this->get(ResourceRequestBuilder::class)
            ->build($request, ResourceRequest::ACTION_INDEX);
        $resource = $this->getDoctrine()->getRepository($resourceRequest->getSubjectClass())->findAll();

        return $resourceRequest->getResponseHandler()->createResponse($resourceRequest, [
            'subjectName' => StringUtility::toSnakeCase($resourceRequest->getSubjectName()),
            'subjectNamePlural' => $resourceRequest->getSubjectNamePlural(),
            'subjectResource' => $resource,
            StringUtility::toSnakeCase($resourceRequest->getSubjectNamePlural()) => $resource,
        ]);
    }

    protected function denyDirectAccess()
    {
        if ($this::class === self::class) {
            throw new AccessDeniedHttpException('This route is not accessible');
        }
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ResourceRequestBuilder::class,
        ]);
    }
}