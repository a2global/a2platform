<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber\OnDataBuild;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetDataSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['setData', 300],
        ];
    }

    public function setData(OnDataBuildEvent $event)
    {
        $event->getDatasheet()->setData($event->getDataCollection());
    }
}