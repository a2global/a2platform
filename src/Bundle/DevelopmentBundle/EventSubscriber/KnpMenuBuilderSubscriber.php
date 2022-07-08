<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 200],
        ];
    }

    public function onSetupMenu(KnpMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Datasheet', [
            'route' => 'admin_development_datasheet',
        ])->setLabelAttribute('icon', 'fas fa-th-large');
    }
}