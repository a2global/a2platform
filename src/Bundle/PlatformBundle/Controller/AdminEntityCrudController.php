<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Entity\EntityDataBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Admin\BuildEntityListEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Provider\FormProvider;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/admin/entity/', name: 'admin_entity_')]
class AdminEntityCrudController extends AbstractController
{
    #[Route('list/{className}', name: 'index')]
    public function indexAction(EventDispatcherInterface $eventDispatcher, $className)
    {
        $event = new BuildEntityListEvent($className);
        $eventDispatcher->dispatch($event, BuildEntityListEvent::NAME);

        return $this->render('@Platform/admin/entity/list.html.twig', [
            'datasheet' => $event->getDatasheet(),
            'entityName' => 'entity.name.single.' . StringUtility::toSnakeCase(StringUtility::getShortClassName($className)),
        ]);
    }

    #[Route('view', name: 'view')]
    public function viewAction(
        EntityManagerInterface $entityManager,
        EntityDataBuilder      $entityDataBuilder,
        Request                $request
    ) {
        $object = $entityManager->getRepository($request->get('className'))->find($request->get('id'));

        return $this->render('@Platform/admin/entity/view.html.twig', [
            'object' => $object,
            'data' => $entityDataBuilder->getData($object),
        ]);
    }

    #[Route('edit', name: 'edit')]
    public function editAction(Request $request, EntityManagerInterface $entityManager, FormProvider $formProvider)
    {
        $objectClassName = $request->get('className');
        $objectId = $request->get('id');
        $object = $entityManager->getRepository($objectClassName)->find($objectId);
        $form = $formProvider->getFor($object);
        $form->setData($object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_entity_view', [
                'className' => $objectClassName,
                'id' => $objectId,
            ]);
        }

        return $this->render('@Platform/admin/entity/edit.html.twig', [
            'object' => $object,
            'form' => $form->createView(),
        ]);
    }
}