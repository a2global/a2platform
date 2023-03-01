<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Entity\WorkflowTransition;
use A2Global\A2Platform\Bundle\PlatformBundle\Provider\FormProvider;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class WorkflowTimeLineBuilder
{
    public function __construct(
        protected Registry                 $workflowRegistry,
        protected EntityManagerInterface   $entityManager,
        protected Environment              $twig,
        protected TranslatorInterface      $translator,
        protected FormProvider             $formProvider,
        protected EventDispatcherInterface $eventDispatcher,
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

        foreach ($pastTransitions as $pastTransition) {
            $event = new OnWorkflowTransitionViewBuild($object, $workflowName, $pastTransition->getTransitionName());
            $this->eventDispatcher->dispatch($event, $event->getName());

            if($event->getParameters()->count() > 0){
                $content = $this->twig->render('@Data/workflow/timeline.parameters.twig', [
                    'parameters' => $event->getParameters(),
                ]);
                $event->addContent($content);
            }
            $name = $this->getTransitionName(
                $pastTransition->getTransitionName(),
                $object,
                $pastTransition->getWorkflowName(),
            );
            $timelineSteps[] = (new TimeLineStep())
                ->setName($name)
                ->setDatetime($pastTransition->getCreatedAt())
                ->setContent($event->getContent());
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