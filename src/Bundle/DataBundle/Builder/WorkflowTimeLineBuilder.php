<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\TimeLineStep;
use A2Global\A2Platform\Bundle\DataBundle\Entity\WorkflowTransition;
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
        $snakeCasedEntityName = StringUtility::toSnakeCase(StringUtility::getShortClassName($object));
        $camelCasedEntityName = StringUtility::toCamelCase(StringUtility::getShortClassName($object));

        foreach ($pastTransitions as $pastTransition) {
            $code = sprintf(
                'workflow.%s.%s.transition.%s.name',
                $snakeCasedEntityName,
                $pastTransition->getWorkflowName() ?: $snakeCasedEntityName,
                $pastTransition->getTransitionName()
            );
            $translated = $this->translator->trans($code);
            $name = $translated === $code ? StringUtility::normalize($pastTransition->getTransitionName()) : $translated;
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
                    'data' => $pastTransition->getData(),
                ]);
            }
            $timelineSteps[] = (new TimeLineStep())
                ->setName($name)
                ->setDatetime($pastTransition->getCreatedAt())
                ->setContent($content ?? null);
        }

        // Next step
        if (count($stateMachine->getEnabledTransitions($object, $workflowName))) {
            $forms = [];

            foreach ($stateMachine->getEnabledTransitions($object) as $transition) {
                $transitionFormTemplateName = sprintf(
                    'admin/workflow/%s/%s/transition.%s.form.html.twig',
                    $camelCasedEntityName,
                    $workflowName ?: $camelCasedEntityName,
                    $transition->getName()
                );

                if (!$this->twig->getLoader()->exists($transitionFormTemplateName)) {
                    continue;
                }
                $forms[$transition->getName()] = $this->twig->render($transitionFormTemplateName, [
                    'object' => $object,
                ]);
            }
            $content = $this->twig->render('@Data/workflow/timeline.transition.html.twig', [
                'workflowName' => $workflowName,
                'object' => $object,
                'transitions' => $stateMachine->getEnabledTransitions($object),
                'forms' => $forms,
            ]);
            $timelineSteps[] = (new TimeLineStep())
                ->setName($this->translator->trans('Next step') . ':')
                ->setContent($content);
        }

        return [
            'object' => $object,
            'steps' => $timelineSteps,
        ];
    }
}