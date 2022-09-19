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
     * @codeCoverageIgnore
     */
    public function viewAction(Request $request, $entity, $id)
    {
        $object = $this->getDoctrine()->getRepository($entity)->find($id);
        $form = $this->get(FormProvider::class)->getFor($object);
        $form->setData($object);

        return $this->render('@Data/entity.html.twig', [
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
        ]);
    }
}