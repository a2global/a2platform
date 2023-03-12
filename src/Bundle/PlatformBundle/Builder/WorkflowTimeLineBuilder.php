<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\TimeLineStep;
use A2Global\A2Platform\Bundle\PlatformBundle\Entity\WorkflowTransition;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Workflow\WorkflowTransitionViewBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Provider\FormProvider;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Registry;
use Twig\Environment;

class WorkflowTimeLineBuilder
{
    public function __construct(
        protected Registry                 $workflowRegistry,
        protected EntityManagerInterface   $entityManager,
        protected Environment              $twig,
        protected FormProvider             $formProvider,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function build($object, $workflowName = null)
    {
        $timelineSteps = [];
        $stateMachine = $this->workflowRegistry->get($object, $workflowName);
        $objectClassNameSnakeCase = StringUtility::toSnakeCase(get_class($object));

        // Past steps
        $pastTransitions = $this->entityManager->getRepository(WorkflowTransition::class)->findBy([
            'targetClass' => get_class($object),
            'targetId' => $object->getId(),
            'workflowName' => $workflowName,
        ], ['id' => 'ASC']);

        foreach ($pastTransitions as $pastTransition) {
            $event = new WorkflowTransitionViewBuildEvent($object, $workflowName, $pastTransition->getTransitionName());
            $this->eventDispatcher->dispatch($event, $event->getName());

            if ($event->getParameters()->count() > 0) {
                $content = $this->twig->render('@Platform/workflow/timeline.parameters.twig', [
                    'parameters' => $event->getParameters(),
                ]);
                $event->addContent($content);
            }
            $name = sprintf(
                '%s.workflow.place.name.%s.%s',
                $objectClassNameSnakeCase,
                $workflowName,
                $this->getTransitionTargetPlace($object, $workflowName, $pastTransition->getTransitionName()),
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
                $title = sprintf(
                    '%s.workflow.transition.name.%s.%s',
                    $objectClassNameSnakeCase,
                    $workflowName,
                    $transition->getName(),
                );
                $availableTransitions[] = [
                    'name' => $transition->getName(),
                    'title' => $title,
                    'form' => $this->formProvider
                        ->getTransitionForm($object, $workflowName, $transition->getName())
                        ->createView(),
                ];
            }
            $content = $this->twig->render('@Platform/workflow/timeline.transition.html.twig', [
                'workflowName' => $workflowName,
                'object' => $object,
                'transitions' => $availableTransitions,
            ]);
            $timelineSteps[] = (new TimeLineStep())
                ->setIsTabs(true)
                ->setName('Next step')
                ->setContent($content);
        }

        return [
            'object' => $object,
            'steps' => $timelineSteps,
        ];
    }

    protected function getTransitionTargetPlace($object, string $workflowName, string $transitionName): string
    {
        foreach ($this->workflowRegistry->get($object, $workflowName)->getDefinition()->getTransitions() as $transition) {
            if ($transition->getName() === $transitionName) {
                $tos = $transition->getTos();

                return reset($tos);
            }
        }

        throw new Exception('Transition place not found');
    }
}