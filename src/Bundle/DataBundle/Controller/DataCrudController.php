<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\DataBundle\Builder\ActionUrlBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Builder\EntityConfigurationBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Builder\EntityDataBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Component\Action;
use A2Global\A2Platform\Bundle\DataBundle\Event\OnEntityListDatasheetBuild;
use A2Global\A2Platform\Bundle\DataBundle\Form\ImportUploadFileFormType;
use A2Global\A2Platform\Bundle\DataBundle\Import\EntityDataImporter;
use A2Global\A2Platform\Bundle\DataBundle\Provider\DatasheetProvider;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use A2Global\A2Platform\Bundle\DataBundle\Registry\DataReaderRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;
use Throwable;
use Twig\Environment;

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
        $event = new OnEntityListDatasheetBuild($entity);
        $this->get(EventDispatcherInterface::class)->dispatch($event);
        $datasheet = $event->getDatasheet()
            ?? $this->get(DatasheetProvider::class)->getDefaultEntityListDatasheet($entity);

        return $this->render('@Admin/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    /**
     * @Route("click/{entity}/{id}", name="click")
     */
    public function clickAction($entity, $id)
    {
        $object = $this->getDoctrine()->getRepository($entity)->find($id);
        $action = $this->getDefaultEntityAction($object);

        if (!$action) {
            return $this->redirectToRoute('admin_data_index', [
                'entity' => $entity,
            ]);
        }

        return $this->redirect($this->get(ActionUrlBuilder::class)->build($action, $object));
    }

    /**
     * @Route("view/{entity}/{id}", name="view")
     */
    public function viewAction($entity, $id)
    {
        $object = $this->getDoctrine()->getRepository($entity)->find($id);

        return $this->render('@Data/entity/view.html.twig', [
            'object' => $object,
            'data' => $this->get(EntityDataBuilder::class)->getData($object),
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_data_view', [
                'entity' => $entity,
                'id' => $id,
            ]);
        }

        return $this->render('@Data/entity/edit.html.twig', [
            'form' => $form->createView(),
            'object' => $object,
        ]);
    }

    /**
     * @Route("mass-edit/{entity}", name="mass_edit")
     */
    public function massEditAction(Request $request, $entity)
    {
        $ids = explode(',', $request->get('ids'));
        $form = $this->get(FormProvider::class)->getMassEditForm($entity, $ids);
//        $object = $this->getDoctrine()->getRepository($entity)->find($id);
//        $form = $this->get(FormProvider::class)->getFor($object);
//        $form->setData($object);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('admin_data_view', [
//                'entity' => $entity,
//                'id' => $id,
//            ]);
//        }
//
        return $this->render('@Admin/form.html.twig', [
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
                    mkdir($filepath, 0777, true); // @codeCoverageIgnore
                }
                $file->move($filepath, $filename . '.' . $extension);
            } catch (Throwable $exception) { // @codeCoverageIgnore
                $this->addFlash('danger', 'There was an error, try again'); // @codeCoverageIgnore

                return $this->redirectToRoute('admin_data_import_upload', [ // @codeCoverageIgnore
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

        if (!file_exists($filepath)) {
            $this->addFlash('warning', 'Please upload CSV file again'); // @codeCoverageIgnore

            return $this->redirectToRoute('admin_data_import_upload', [ // @codeCoverageIgnore
                'entity' => $entity,
            ]);
        }
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
            unlink($filepath);

            return $this->render('@Data/entity/import_result.html.twig', [
                'result' => $result,
            ]);
        }

        return $this->render('@Admin/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    protected function getDefaultEntityAction($object): ?Action
    {
        $entityConfiguration = $this->get(EntityConfigurationBuilder::class)->build($object);

        foreach ($entityConfiguration->getActions() as $action) {
            if ($action->getName() == $entityConfiguration->getDefaultAction()) {
                return $action;
            }
        }

        return null;
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
            EventDispatcherInterface::class,
            DatasheetProvider::class,
            Environment::class,
            Registry::class,
            EntityDataBuilder::class,
            EntityConfigurationBuilder::class,
            ActionUrlBuilder::class,
        ]);
    }
}