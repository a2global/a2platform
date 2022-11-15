<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\EntityConfiguration;

use A2Global\A2Platform\Bundle\DataBundle\Component\EntityAction;
use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class CommentsActionSubscriber implements EventSubscriberInterface
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
            EntityConfigurationBuildEvent::class => ['addAction', 100],
        ];
    }

    public function addAction(EntityConfigurationBuildEvent $event)
    {
        $entityAction = (new EntityAction())
            ->setName('Comments')
            ->setUrl($this->router->generate('admin_data_comment_list', [
                'entity' => $event->getClassname(),
                'id' => $event->getObject()->getId(),
            ]));
        $event->getConfiguration()->addAction($entityAction);
    }
}