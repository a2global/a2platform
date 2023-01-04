<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\EntityConfiguration;

use A2Global\A2Platform\Bundle\DataBundle\Component\Action;
use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentsActionSubscriber implements EventSubscriberInterface
{
    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EntityConfigurationBuildEvent::class => ['addAction', 100],
        ];
    }

    public function addAction(EntityConfigurationBuildEvent $event)
    {
        $entityAction = (new Action())
            ->setName('comments')
            ->setRouteName('admin_data_comment_list')
            ->setRouteParameters([
                'entity' => $event->getClassname(),
            ]);
        $event->getConfiguration()->addAction($entityAction);
    }
}