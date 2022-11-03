<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\TimeLineStep;
use A2Global\A2Platform\Bundle\DataBundle\Entity\WorkflowTransition;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class WorkflowTimeLineBuilder
{
    public function __construct(
        protected Registry               $workflowRegistry,
        protected EntityManagerInterface $entityManager,
        protected Environment            $twig,
        protected TranslatorInterface    $translator,
        protected FormProvider           $formProvider,
    ) {
    }

    public function build($object, $workflowName = null)
    {
        $timelineSteps = [];

        $stateMachine = $this->workflowRegistry->get($object, $workflowName);

        // Past steps
        $pastTransitions = $this->entityManager->getRepository(WorkflowTransition::class)->findBy([
            'targetClass' => get_class($object),
            'targetId' => $object->getId(),
            'workflowName' => $workflowName,
        ], ['id' => 'ASC']);
        $camelCasedEntityName = StringUtility::toCamelCase(StringUtility::getShortClassName($object));

        foreach ($pastTransitions as $pastTransition) {
            $transitionDetailsTemplateName = sprintf(
                'admin/workflow/%s/%s/transition.%s.details.html.twig',
                $camelCasedEntityName,
                $pastTransition->getWorkflowName() ?: $camelCasedEntityName,
                $pastTransition->getTransitionName()
            );
            $content = null;

            if ($this->twig->getLoader()->exists($transitionDetailsTemplateName)) {
                $content = $this->twig->render($transitionDetailsTemplateName, [
                    'object' => $object,
                    'context' => $pastTransition->getContext(),
                ]);
            }
            $name = $this->getTransitionName($pastTransition->getTransitionName(), $object, $pastTransition->getWorkflowName());
            $timelineSteps[] = (new TimeLineStep())
                ->setName($name)
                ->setDatetime($pastTransition->getCreatedAt())
                ->setContent($content ?? null);
        }

        // Next step
        $availableTransitions = [];

        if (count($stateMachine->getEnabledTransitions($object))) {
            foreach ($stateMachine->getEnabledTransitions($object) as $transition) {
                $availableTransitions[] = [
                    'name' => $transition->getName(),
                    'title' => $this->getTransitionName($transition->getName(), $object, $workflowName),
                    'form' => $this->formProvider
                        ->getTransitionForm($object, $workflowName, $transition->getName())
                        ->createView(),
                ];
//                $transitionFormTemplateName = sprintf(
//                    'admin/workflow/%s/%s/transition.%s.form.html.twig',
//                    $camelCasedEntityName,
//                    $workflowName ?: $camelCasedEntityName,
//                    $transition->getName()
//                );
//
//                if ($this->twig->getLoader()->exists($transitionFormTemplateName)) {
//                    $availableTransition['form'] = $this->twig->render($transitionFormTemplateName, [
//                        'object' => $object,
//                    ]);
//                }
//                $availableTransitions[] = $availableTransition;
            }
            $content = $this->twig->render('@Data/workflow/timeline.transition.html.twig', [
                'workflowName' => $workflowName,
                'object' => $object,
                'transitions' => $availableTransitions,
            ]);
            $timelineSteps[] = (new TimeLineStep())
                ->setIsTabs(true)
                ->setName($this->translator->trans('Next step') . ':')
                ->setContent($content);
        }

        return [
            'object' => $object,
            'steps' => $timelineSteps,
        ];
    }

    protected function getTransitionName($transitionName, $object, $workflowName = null)
    {
        $snakeCasedEntityName = StringUtility::toSnakeCase(StringUtility::getShortClassName($object));
        $code = sprintf(
            'workflow.%s.%s.transition.%s.name',
            $snakeCasedEntityName,
            $workflowName ?: $snakeCasedEntityName,
            $transitionName
        );
        $translated = $this->translator->trans($code);

        return $translated === $code ? StringUtility::normalize($transitionName) : $translated;
    }
}