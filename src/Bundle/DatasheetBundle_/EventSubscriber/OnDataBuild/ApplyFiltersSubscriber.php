<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber\OnDataBuild;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\DatasheetFilterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplyFiltersSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['applyFilters', 450],
        ];
    }

    public function applyFilters(OnDataBuildEvent $event)
    {
        /** @var DatasheetFilterInterface $filter */
        foreach ($event->getDatasheet()->getFilters() as $filter) {
            $event->getDataReader()->addFilter($filter);
        }
    }
}