<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnAfterBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Registry\DatasheetFilterRegistry;

class CollectFiltersViewDataSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnAfterBuildEvent::class => ['collectFilterViewData', 100],
        ];
    }

    public function __construct(
        protected DatasheetFilterRegistry $datasheetFilterRegistry
    ) {
    }

    public function collectFilterViewData(OnAfterBuildEvent $event)
    {
        /** @var FilterInterface $filter */
        foreach($event->getDatasheet()->getFilters() as $filter){
//            dd($filter);
        }
    }
}