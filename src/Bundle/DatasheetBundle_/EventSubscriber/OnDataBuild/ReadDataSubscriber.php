<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber\OnDataBuild;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ReadDataSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['readData', 400],
        ];
    }

    public function readData(OnDataBuildEvent $event)
    {
        $event->setDataCollection($event->getDataReader()->getData());
    }
}