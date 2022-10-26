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
     * @Route("apply_transition", name="apply_transition")
     */
    public function applyTransitionAction(Request $request)
    {
        $workflowName = $request->get('workflowName') ?: null;
        $transitionName = $request->get('transitionName');
        $objectClass = $request->get('objectClass');
        $objectId = $request->get('objectId');
        $object = $this->getDoctrine()->getRepository($objectClass)->find($objectId);
        $stateMachine = $this->get(Registry::class)->get($object, $workflowName);

        try {
            if (!$stateMachine->can($object, $transitionName)) {
                throw new Exception('This transition is not possible');
            }
            $context = $this->get('request_stack')->getCurrentRequest()->request->all();
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
        } catch (Throwable $exception) {
            $this->addFlash('danger', $exception->getMessage() . ' in ' . $exception->getTraceAsString());
        }

        return $this->get(ControllerHelper::class)->redirectBackOrTo(
            $this->generateUrl('admin_data_view', [
                'entity' => $objectClass,
                'id' => $objectId,
            ])
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