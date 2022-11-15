<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\AdminEntityMenu;

use A2Global\A2Platform\Bundle\AdminBundle\Builder\MenuBuilder;
use A2Global\A2Platform\Bundle\AdminBundle\Event\EntityMenuBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class ViewMenuItemSubscriber implements EventSubscriberInterface
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
            EntityMenuBuildEvent::class => ['addViewItem', 990],
        ];
    }

    public function addViewItem(EntityMenuBuildEvent $event)
    {
        $event->getMenu()->addChild('view', [
            'route' => 'admin_data_view',
            'routeParameters' => [
                'entity' => $event->getEntityClassname(),
                'id' => $event->getObject()->getId(),
            ],
            'extras' => [
                MenuBuilder::IS_CURRENT_URL_ALIASES_KEY => [
                    $this->router->generate('admin_data_edit', [
                        'entity' => $event->getEntityClassname(),
                        'id' => $event->getObject()->getId(),
                    ]),
                ],
            ],
        ]);
    }
}