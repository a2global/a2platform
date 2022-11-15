<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\EntityConfiguration;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\EntityAction;
use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;

class WorkflowActionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected RouterInterface $router,
        protected Registry $registry,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EntityConfigurationBuildEvent::class => ['addAction', 500],
        ];
    }

    public function addAction(EntityConfigurationBuildEvent $event)
    {
        /** @var StateMachine $stateMachine */
        foreach ($this->registry->all($event->getObject()) as $stateMachine) {
            $action = (new EntityAction())
                ->setName('workflow_' . StringUtility::toSnakeCase($stateMachine->getName()))
                ->setUrl($this->router->generate('admin_data_workflow_view', [
                    'entity' => $event->getClassname(),
                    'id' => $event->getObject()->getId(),
                    'workflow' => $stateMachine->getName(),
                ]))
                ->setTitle($stateMachine->getName());
            $event->getConfiguration()->addAction($action);
        }
    }
}