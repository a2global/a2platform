<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use A2Global\A2Platform\Bundle\PlatformBundle\Entity\WorkflowTransition;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\ControllerHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;
use Throwable;

#[Route('/admin/entity/workflow/', name: 'admin_entity_workflow_')]
class AdminEntityWorkflowController extends AbstractController
{
    #[Route('view/{className}/{id}/{workflow}', name: 'view')]
    public function viewAction(
        Request                $request,
        EntityManagerInterface $entityManager,
                               $className,
                               $id,
                               $workflow
    ) {
        $object = $entityManager->getRepository($className)->find($id);

        return $this->render('@Platform/admin/entity/workflow.html.twig', [
            'workflow' => $workflow,
            'object' => $object,
        ]);
    }


    #[Route('apply_transition', name: 'apply_transition')]
    public function applyTransitionAction(
        Request                $request,
        EntityManagerInterface $entityManager,
        Registry               $workflowRegistry,
        ControllerHelper       $controllerHelper,
    ) {
        $requestData = $request->request->all();
        $context = $requestData['form'];
        $workflowName = $context['workflowName'] ?: null;
        $transitionName = $context['transitionName'];
        $objectClass = $context['objectClass'];
        $objectId = $context['objectId'];
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

        return $controllerHelper->redirectBackOrTo(
            $this->generateUrl('admin_entity_view', [
                'entity' => $objectClass,
                'id' => $objectId,
                'general_tab' => 'workflow_' . $workflowName,
            ]),
            ['general_tab' => 'workflow_' . $workflowName,]
        );
    }
}