<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\EntityConfiguration;

use A2Global\A2Platform\Bundle\DataBundle\Component\EntityAction;
use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class ViewActionSubscriber implements EventSubscriberInterface
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
        $entityAction = (new EntityAction())
            ->setName('View')
            ->setUrl($this->router->generate('admin_data_view', [
                'entity' => $event->getClassname(),
                'id' => $event->getObject()->getId(),
            ]))
            ->setIsDefault(true);
        $event->getConfiguration()->addAction($entityAction);
    }
}