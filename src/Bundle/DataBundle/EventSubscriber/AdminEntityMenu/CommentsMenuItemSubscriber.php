<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\AdminEntityMenu;

use A2Global\A2Platform\Bundle\AdminBundle\Builder\MenuBuilder;
use A2Global\A2Platform\Bundle\AdminBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Entity\CommentableEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class CommentsMenuItemSubscriber implements EventSubscriberInterface
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
            EntityMenuBuildEvent::class => 'addCommentsItem',
        ];
    }

    public function addCommentsItem(EntityMenuBuildEvent $event)
    {
        if (!$event->getObject() instanceof CommentableEntityInterface) {
            return;
        }
        $event->getMenu()->addChild('comments', [
            'route' => 'admin_data_comment_list',
            'routeParameters' => [
                'entity' => $event->getEntityClassname(),
                'id' => $event->getObject()->getId(),
            ],
        ]);
    }
}