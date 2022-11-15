<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\ControllerHelper;
use A2Global\A2Platform\Bundle\DataBundle\Entity\WorkflowTransition;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * @Route("admin/data/workflow/", name="admin_data_workflow_")
 */
class DataWorkflowController extends AbstractController
{
    /**
     * @Route("view/{entity}/{id}/{workflow}", name="view")
     */
    public function viewAction(Request $request, $entity, $id, $workflow)
    {
        $object = $this->getDoctrine()->getRepository($entity)->find($id);

        return $this->render('@Data/entity/workflow.html.twig', [
            'object' => $object,
            'workflow' => $workflow,
        ]);
    }

    /**
     * @Route("apply_transition", name="apply_transition")
     */
    public function applyTransitionAction(Request $request)
    {
        $context = $request->request->get('form');
        $workflowName = $context['workflowName'] ?: null;
        $transitionName = $context['transitionName'];
        $objectClass = $context['objectClass'];
        $objectId = $context['objectId'];
        $object = $this->getDoctrine()->getRepository($objectClass)->find($objectId);
        $stateMachine = $this->get(Registry::class)->get($object, $workflowName);

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
            $this->getDoctrine()->getManager()->persist($workflowTransition);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Saved');
        } catch (Throwable $exception) { // @codeCoverageIgnore
            $this->addFlash('danger', $exception->getMessage() . ' in ' . $exception->getTraceAsString()); // @codeCoverageIgnore
        }

        return $this->get(ControllerHelper::class)->redirectBackOrTo(
            $this->generateUrl('admin_data_view', [
                'entity' => $objectClass,
                'id' => $objectId,
                'general_tab' => 'workflow_' . $workflowName,
            ]),
            ['general_tab' => 'workflow_' . $workflowName,]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            Registry::class,
            TranslatorInterface::class,
            ControllerHelper::class,
        ]);
    }
}