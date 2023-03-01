<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\MenuItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;

#[AsEventListener(event: 'a2platform.menu.build.entity.single', method: 'singleEntityMenuBuild', priority: 900)]
class EntityMenuBuildEventListener
{
    public function __construct(
        protected RouterInterface $router,
        protected EntityHelper    $entityHelper,
        protected Registry        $registry,
    ) {
    }

    public function singleEntityMenuBuild(EntityMenuBuildEvent $event): void
    {
        $object = new ($event->getClassName());

        $menuItem = (new MenuItem('View'))
            ->setRouteName('admin_entity_view')
            ->setRouteParameters([
                'className' => $event->getClassName(),
            ]);
        $event->getMenu()->addItem($menuItem);

        $menuItem = (new MenuItem('Edit'))
            ->setRouteName('admin_entity_edit')
            ->setRouteParameters([
                'className' => $event->getClassName(),
            ]);
        $event->getMenu()->addItem($menuItem);

        /**
         * todo: change to classname to avoid creating an empty object?
         * @var StateMachine $stateMachine
         */
        foreach ($this->registry->all($object) as $stateMachine) {
            $menuItem = (new MenuItem(StringUtility::toReadable($stateMachine->getName())))
                ->setRouteName('admin_entity_workflow_view')
                ->setRouteParameters([
                    'className' => $event->getClassName(),
                    'workflow' => $stateMachine->getName(),
                ]);
            $event->getMenu()->addItem($menuItem);
        }
    }
}
