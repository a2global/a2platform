<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\FilterProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplyFilters implements EventSubscriberInterface
{
    public function __construct(
        protected FilterProvider $filterProvider
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['applyFilters', 450],
        ];
    }

    public function applyFilters(OnDataBuildEvent $event)
    {
        foreach ($this->filterProvider->getFilters($event->getDatasheet()->getId()) as $filter) {
            $event->getDataReader()->addFilter($filter);
        }
    }
}