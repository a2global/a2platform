<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\EntityConfiguration;

use A2Global\A2Platform\Bundle\DataBundle\Component\Action;
use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ViewActionSubscriber implements EventSubscriberInterface
{
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
            ->setName('view')
            ->setRouteName('admin_data_view')
            ->setRouteParameters([
                'entity' => $event->getClassname(),
            ]);
        $event->getConfiguration()->addAction($entityAction);
        $event->getConfiguration()->setDefaultAction('view');
    }
}