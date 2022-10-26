<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\ControllerHelper;
use A2Global\A2Platform\Bundle\DataBundle\Entity\Comment;
use A2Global\A2Platform\Bundle\DataBundle\Entity\Tag;
use A2Global\A2Platform\Bundle\DataBundle\Entity\WorkflowTransition;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

/**
 * @Route("admin/data/workflow", name="admin_data_workflow_")
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
        $transitionData = $request->get('data');

        try {
            $stateMachine->apply($object, $transitionName);
            $workflowTransition = (new WorkflowTransition())
                ->setTargetClass($objectClass)
                ->setTargetId($objectId)
                ->setWorkflowName($workflowName)
                ->setTransitionName($transitionName);

            if($transitionData){
                $workflowTransition->setData($transitionData);
            }
            $this->getDoctrine()->getManager()->persist($workflowTransition);
            $this->getDoctrine()->getManager()->flush();
        } catch (Throwable $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->get(ControllerHelper::class)->redirectBackOrTo(
            $this->generateUrl('admin_data_view', [
                'entity' => $object,
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