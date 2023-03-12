<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Entity\EntityDataBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Entity\EntityComment;
use A2Global\A2Platform\Bundle\PlatformBundle\Entity\WorkflowTransition;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Admin\BuildEntityListEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\ControllerHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Manager\EntityCommentManager;
use A2Global\A2Platform\Bundle\PlatformBundle\Provider\FormProvider;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Throwable;

#[Route('/admin/', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('', name: 'default')]
    public function defaultAction()
    {
        return $this->render('@Platform/admin/index.html.twig');
    }

    #[Route('entity/list/{className}', name: 'entity_index')]
    public function entityIndexAction(EventDispatcherInterface $eventDispatcher, $className)
    {
        $event = new BuildEntityListEvent($className);
        $eventDispatcher->dispatch($event, BuildEntityListEvent::NAME);

        return $this->render('@Platform/admin/entity/list.html.twig', [
            'datasheet' => $event->getDatasheet(),
            'entityName' => 'entity.name.single.' . StringUtility::toSnakeCase(StringUtility::getShortClassName($className)),
        ]);
    }

    #[Route('entity/view', name: 'entity_view')]
    public function entityViewAction(
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

    #[Route('entity/comments', name: 'entity_comments')]
    public function entityCommentsAction(
        EntityManagerInterface $entityManager,
        FormProvider           $formProvider,
        Request                $request
    ) {
        $objectClassName = $request->get('className');
        $objectId = $request->get('id');
        $object = $entityManager->getRepository($objectClassName)->find($objectId);
        $form = $formProvider->getCommentForm($object);

        if ($request->getMethod() === Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($form->getData());
                $entityManager->flush();

                return $this->redirectToRoute('admin_entity_comments', [
                    'className' => $objectClassName,
                    'id' => $objectId,
                ]);
            }
        }
        $comments = $entityManager->getRepository(EntityComment::class)->findBy([
            'className' => $objectClassName,
            'entityId' => $objectId,
        ], ['id' => 'DESC']);

        return $this->render('@Platform/admin/entity/comments.html.twig', [
            'object' => $object,
            'comments' => $comments,
            'form' => $form,
        ]);
    }

    #[Route('entity/edit', name: 'entity_edit')]
    public function entityEditAction(Request $request, EntityManagerInterface $entityManager, FormProvider $formProvider)
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

    #[Route('entity/workflow/view/{className}/{id}/{workflow}', name: 'entity_workflow_view')]
    public function workflowViewAction(EntityManagerInterface $entityManager, $className, $id, $workflow)
    {
        return $this->render('@Platform/admin/entity/workflow.html.twig', [
            'workflow' => $workflow,
            'object' => $entityManager->getRepository($className)->find($id),
        ]);
    }

    #[Route('entity/workflow/apply-transition', name: 'entity_workflow_apply_transition')]
    public function workflowApplyTransitionAction(
        Request                $request,
        EntityManagerInterface $entityManager,
        Registry               $workflowRegistry,
    ) {
        $requestData = $request->request->all();
        $context = $requestData['form'];
        $workflowName = $context['workflowName'] ?: null;
        $transitionName = $context['transitionName'];
        $objectClass = $context['objectClass'];
        $objectId = (int)$context['objectId'];
        $object = $entityManager->getRepository($objectClass)->find($objectId);
        $stateMachine = $workflowRegistry->get($object, $workflowName);

        try {
            if (!$stateMachine->can($object, $transitionName)) {
                throw new Exception('This transition is not possible'); // @codeCoverageIgnore
            }
            $stateMachine->apply($object, $transitionName, $context);
            $workflowTransition = (new WorkflowTransition())
                ->setTargetClass($objectClass)
                ->setTargetId($objectId)
                ->setWorkflowName($workflowName)
                ->setTransitionName($transitionName)
                ->setContext($context);
            $entityManager->persist($workflowTransition);
            $entityManager->flush();
            $this->addFlash('success', 'Saved');
        } catch (Throwable $exception) { // @codeCoverageIgnore
            $this->addFlash('danger', $exception->getMessage() . ' in ' . $exception->getTraceAsString()); // @codeCoverageIgnore
        }

        return $this->redirectToRoute('admin_entity_workflow_view', [
            'className' => $objectClass,
            'id' => $objectId,
            'workflow' => $workflowName,
        ]);
    }
}
