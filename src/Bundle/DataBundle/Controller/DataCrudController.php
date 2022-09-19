<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use Doctrine\ORM\AbstractQuery;
use PhpParser\Builder\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/data/", name="admin_data_")
 */
class DataCrudController extends AbstractController
{
    /**
     * @Route("index/{entity}", name="index")
     */
    public function indexAction($entity)
    {
        $datasheet = $this->getIndexDatasheet($entity);

        return $this->render('@Admin/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    /**
     * @Route("view/{entity}/{id}", name="view")
     */
    public function viewAction(Request $request, $entity, $id)
    {
        $object = $this->getDoctrine()->getRepository($entity)->find($id);
        $data = [];

        foreach (EntityHelper::getEntityFields($object) as $fieldName => $fieldType) {
            $dataType = $this->get(EntityHelper::class)->resolveDataTypeByFieldType($fieldType);
            $data[$fieldName] = $dataType::getReadablePreview(ObjectHelper::getProperty($object, $fieldName));
        }

        return $this->render('@Data/entity/view.html.twig', [
            'data' => $data,
            'editUrl' => $this->generateUrl('admin_data_edit', [
                'entity' => $entity,
                'id' => $id,
            ]),
        ]);
    }

    /**
     * @Route("edit/{entity}/{id}", name="edit")
     */
    public function editAction(Request $request, $entity, $id)
    {
        $object = $this->getDoctrine()->getRepository($entity)->find($id);
        $form = $this->get(FormProvider::class)->getFor($object);
        $form->setData($object);

        if ($request->getMethod() === Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('admin_data_view', [
                    'entity' => $entity,
                    'id' => $id,
                ]);
            }
        }


        return $this->render('@Data/entity/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    protected function getIndexDatasheet($entityClassName): Datasheet
    {
        $datasheet = new Datasheet(
            $this->getDoctrine()->getRepository($entityClassName)->createQueryBuilder('a'),
            'List of the ' . StringUtility::normalize(StringUtility::getShortClassName($entityClassName)),
        );
        $datasheet->getColumn($this->resolveIdentityColumnName($entityClassName))
            ->setLink(['admin_data_view', ['entity' => $entityClassName]])
            ->setBold(true);

        return $datasheet;
    }

    protected function resolveIdentityColumnName($entityClassName): string
    {
        foreach (EntityHelper::getEntityFields($entityClassName) as $fieldName => $fieldType) {
            if (in_array($fieldName, ObjectHelper::$identityFields)) {
                return $fieldName;
            }
        }

        return 'id';
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            FormProvider::class,
            EntityHelper::class,
        ]);
    }
}