<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected EntityHelper $entityHelper,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 100],
        ];
    }

    public function onSetupMenu(KnpMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('a2platform', [
            'label' => 'SETTINGS',
        ])->setAttribute('class', 'header');

        $menu->addChild('Settings', [
            'route' => 'admin_settings',
        ])->setLabelAttribute('icon', 'fas fa-th-large');
    }
}