<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\EntityConfiguration;

use A2Global\A2Platform\Bundle\DataBundle\Component\Action;
use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class MassEditActionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected RouterInterface $router,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EntityConfigurationBuildEvent::class => ['addAction', 990],
        ];
    }

    public function addAction(EntityConfigurationBuildEvent $event)
    {
        $entityAction = (new Action())
            ->setName('Edit')
            ->setRouteName('admin_data_mass_edit')
            ->setRouteParameters([
                'entity' => $event->getClassname(),
            ]);
        $event->getConfiguration()->addMassAction($entityAction);
    }
}