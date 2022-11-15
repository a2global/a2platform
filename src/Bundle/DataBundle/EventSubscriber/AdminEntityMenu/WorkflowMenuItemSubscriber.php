<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\AdminEntityMenu;

use A2Global\A2Platform\Bundle\AdminBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Contracts\Translation\TranslatorInterface;

class WorkflowMenuItemSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected Registry $registry,
        protected TranslatorInterface $translator,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EntityMenuBuildEvent::class => ['addWorkflowItems', 980],
        ];
    }

    public function addWorkflowItems(EntityMenuBuildEvent $event)
    {
        /** @var StateMachine $stateMachine */
        foreach ($this->registry->all($event->getObject()) as $stateMachine) {
            $event->getMenu()->addChild('workflow_' . StringUtility::toSnakeCase($stateMachine->getName()), [
                'route' => 'admin_data_workflow_view',
                'routeParameters' => [
                    'entity' => $event->getEntityClassname(),
                    'id' => $event->getObject()->getId(),
                    'workflow' => $stateMachine->getName(),
                ],
                'label' => $stateMachine->getName(),
            ]);
        }
    }
}