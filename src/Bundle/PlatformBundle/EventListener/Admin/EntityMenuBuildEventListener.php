<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\MenuItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;

#[AsEventListener(event: 'a2platform.menu.build.entity.single', method: 'singleEntityMenuBuildEarly', priority: 900)]
#[AsEventListener(event: 'a2platform.menu.build.entity.single', method: 'singleEntityMenuBuildLate', priority: -800)]
#[AsEventListener(event: 'a2platform.menu.build.entity.single', method: 'setDefault', priority: -900)]
class EntityMenuBuildEventListener
{
    private const WORKFLOW_VIEW_ROUTE_NAME = 'admin_entity_workflow_view';

    public function __construct(
        protected RouterInterface $router,
        protected EntityHelper    $entityHelper,
        protected Registry        $registry,
    ) {
    }

    public function singleEntityMenuBuildEarly(EntityMenuBuildEvent $event): void
    {
        $object = new ($event->getClassName());
        $classNameSnakeCase = StringUtility::toSnakeCase($event->getClassName());

        if (!$event->getMenu()->hasItem('view')) {
            $menuItem = (new MenuItem('view'))
                ->setText(sprintf('%s.menu.single.view', $classNameSnakeCase))
                ->setRouteName('admin_entity_view')
                ->setRouteParameters([
                    'className' => $event->getClassName(),
                ]);
            $event->getMenu()->addItem($menuItem);
        }

        if (!$event->getMenu()->hasItem('edit')) {
            $menuItem = (new MenuItem('edit'))
                ->setText(sprintf('%s.menu.single.edit', $classNameSnakeCase))
                ->setRouteName('admin_entity_edit')
                ->setRouteParameters([
                    'className' => $event->getClassName(),
                ]);
            $event->getMenu()->addItem($menuItem);
        }

        /**
         * todo: change to classname to avoid creating an empty object?
         * @var StateMachine $stateMachine
         */
        foreach ($this->registry->all($object) as $stateMachine) {
            $workflowName = $stateMachine->getName();
            $menuItemName = StringUtility::toSnakeCase($stateMachine->getName());

            if ($event->getMenu()->hasItem($menuItemName)) {
                continue;
            }
            $menuItem = (new MenuItem($menuItemName))
                ->setText(sprintf('%s.workflow.name.%s', $classNameSnakeCase, $menuItemName))
                ->setRouteName('admin_entity_workflow_view')
                ->setRouteParameters([
                    'className' => $event->getClassName(),
                    'workflow' => $workflowName,
                ])
                ->setIsActiveHandler(function (Request $request) use ($workflowName) {
                    if (self::WORKFLOW_VIEW_ROUTE_NAME !== $request->attributes->get('_route')) {
                        return false;
                    }

                    if ($request->attributes->get('workflow') !== $workflowName) {
                        return false;
                    }

                    return true;
                });
            $event->getMenu()->addItem($menuItem);
        }
    }

    public function singleEntityMenuBuildLate(EntityMenuBuildEvent $event): void
    {
        if (!$event->getMenu()->hasItem('comments')) {
            $menuItem = (new MenuItem('comments'))
                ->setRouteName('admin_entity_comments')
                ->setRouteParameters([
                    'className' => $event->getClassName(),
                ]);
            $event->getMenu()->addItem($menuItem);
        }
    }

    public function setDefault(EntityMenuBuildEvent $event)
    {
        foreach ($event->getMenu()->getItems() as $menuItem) {
            if ($menuItem->isDefault()) {
                return;
            }
        }

        if ($event->getMenu()->hasItem('view')) {
            $event->getMenu()->getItem('view')->setIsDefault(true);
        }
    }
}
