<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use A2Global\A2Platform\Bundle\DataBundle\Form\ImportUploadFileFormType;
use A2Global\A2Platform\Bundle\DataBundle\Import\EntityDataImporter;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use A2Global\A2Platform\Bundle\DataBundle\Registry\DataReaderRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

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

    /**
     * @Route("import/upload/{entity}", name="import_upload")
     */
    public function importUploadAction(Request $request, $entity)
    {
        $form = $this->createForm(ImportUploadFileFormType::class);
        $form->get('entity')->setData($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $extension = $file->guessClientExtension();
            $filename = uniqid() . md5(microtime(true));
            $filepath = $this->getParameter('kernel.cache_dir') . '/' . 'import';

            try {
                if (!file_exists($filepath)) {
                    mkdir($filepath, 0777, true);
                }
                $file->move($filepath, $filename . '.' . $extension);
            } catch (Throwable $exception) {
                $this->addFlash('danger', 'There was an error, try again');

                return $this->redirectToRoute('admin_data_import_upload', [
                    'entity' => $entity,
                ]);
            }

            return $this->redirectToRoute('admin_data_import_mapping', [
                'entity' => $entity,
                'filename' => $filename,
                'filetype' => $file->guessClientExtension(),
            ]);
        }

        return $this->render('@Admin/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("import/mapping/{entity}", name="import_mapping")
     */
    public function importMappingAction(Request $request, $entity)
    {
        $extension = $request->get('filetype');
        $filename = $request->get('filename');
        $filepath = $this->getParameter('kernel.cache_dir') . '/' . 'import' . '/' . $filename . '.' . $extension;
        $form = $this->get(FormProvider::class)->getImportMappingFormProvider($entity, $filepath, $filename, $extension);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $result = $this->get(EntityDataImporter::class)->import(
                    $request->get('entity'),
                    $this->get(DataReaderRegistry::class)->findDataReader($filepath)->readData(),
                    $form->get('mapping')->getData(),
                    $form->get('strategy')->getData(),
                    $form->get('identifier_field')->getData(),
                );
                //unlink($filepath);

            return $this->render('@Data/entity/import_result.html.twig', [
                'result' => $result,
            ]);
        }

        return $this->render('@Admin/form.html.twig', [
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
        $datasheet->addControl('Import', $this->generateUrl('admin_data_import_upload', ['entity' => $entityClassName]));

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
            EntityDataImporter::class,
            DataReaderRegistry::class,
        ]);
    }
}