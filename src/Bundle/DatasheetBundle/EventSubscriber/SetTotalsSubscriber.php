<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetTotalsSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['setTotals', 100],
        ];
    }

    public function setTotals(OnDataBuildEvent $event)
    {
        $event->getDatasheet()->setItemsTotal($event->getDataReader()->getItemsTotal());
    }
}
