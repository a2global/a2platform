<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Builder\ResourceRequestBuilder;
use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Entity\TaggableEntityInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

abstract class AbstractAdminController extends AbstractController
{
    #[Route('index', name: 'index')]
    public function indexAction(Request $request)
    {
        $this->denyDirectAccess();
        $resourceRequest = $this->get(ResourceRequestBuilder::class)
            ->build($request, ResourceRequest::ACTION_INDEX, true);

        return $resourceRequest->getResponseHandler()->createResponse($resourceRequest, [
            'subjectName' => StringUtility::toSnakeCase($resourceRequest->getSubjectName()),
            'subjectNamePlural' => $resourceRequest->getSubjectNamePlural(),
            'datasheet' => $this->getIndexDatasheet($resourceRequest),
        ]);
    }

    #[Route('view/{id}', name: 'view')]
    public function viewAction(Request $request, $id)
    {
        $this->denyDirectAccess();
        $resourceRequest = $this->get(ResourceRequestBuilder::class)
            ->build($request, ResourceRequest::ACTION_VIEW, true);

        $object = $this->getDoctrine()
            ->getRepository($resourceRequest->getSubjectClass())
            ->find($id);

        if (!$object) {
            throw new NotFoundHttpException();
        }

        return $resourceRequest->getResponseHandler()->createResponse($resourceRequest, [
            'object' => $object,
            'editUrl' => $this->generateUrl($resourceRequest->getRouteNameEdit(), ['id' => $object->getId()]),
            'objectName' => StringUtility::toSnakeCase($resourceRequest->getSubjectName()),
        ]);
    }

    #[Route('edit/{id}', name: 'edit')]
    public function editAction(Request $request, $id)
    {
        $this->denyDirectAccess();
        $resourceRequest = $this->get(ResourceRequestBuilder::class)
            ->build($request, ResourceRequest::ACTION_EDIT, true);

        $object = $this->getDoctrine()
            ->getRepository($resourceRequest->getSubjectClass())
            ->find($id);

        if (!$object) {
            throw new NotFoundHttpException();
        }

        return $resourceRequest->getResponseHandler()->createResponse($resourceRequest, [
            'object' => $object,
            'objectName' => StringUtility::toSnakeCase($resourceRequest->getSubjectName()),
        ]);
    }

    protected function denyDirectAccess()
    {
        if ($this::class === self::class) {
            throw new AccessDeniedHttpException('This route is not accessible');
        }
    }

    protected function getIndexDatasheet(ResourceRequest $resourceRequest): Datasheet
    {
        $datasheet = new Datasheet(
            $queryBuilder = $this->getDoctrine()
                ->getRepository($resourceRequest->getSubjectClass())
                ->createQueryBuilder('a')
        );
        $datasheet
            ->setTitle('List of the ' . $resourceRequest->getSubjectName());

        $datasheet
            ->getColumn('id')
            ->setType(NumberColumn::class)
            ->setWidth(200);

        if (is_subclass_of($resourceRequest->getSubjectClass(), TaggableEntityInterface::class)) {
            $tagsColumn = (new TagsColumn('tags'))
                ->setPosition(1);
            $datasheet->addColumn($tagsColumn);
        }

        return $datasheet;
    }

    protected function buildObjectData($object)
    {
        $fields = QueryBuilderUtility::getEntityFields(get_class($object));
        $data = [];

        foreach ($fields as $field) {
            $getter = 'get' . $field['name'];
            $data[$field['name']] = [
                'name' => StringUtility::normalize($field['name']),
                'value' => '',
            ];

            if (!method_exists($object, $getter)) {
                continue;
            }

            try {
                $value = $object->$getter();
            } catch (Throwable $exception) {
                continue;
            }

            if (is_scalar($value)) {
                $data[$field['name']]['value'] = (string)$value;
            }
        }

        return $data;
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            DatasheetBuilder::class,
            ResourceRequestBuilder::class,
        ]);
    }
}