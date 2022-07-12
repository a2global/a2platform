<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\DatasheetFilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\PaginationDatasheetFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SetPaginationDataSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['setPaginationData', 100],
        ];
    }

    public function setPaginationData(OnDataBuildEvent $event)
    {
        $this->setPaginationFilterData($event->getDatasheet());
        $event->getDatasheet()->setItemsTotal($event->getDataReader()->getItemsTotal());
    }

    protected function setPaginationFilterData(DatasheetExposed $datasheet)
    {
        /** @var DatasheetFilterInterface $filter */
        foreach ($datasheet->getFilters() as $filter) {
            if ($filter instanceof PaginationDatasheetFilter) {
                $datasheet
                    ->setPage($filter->getDataFilter()->getPage())
                    ->setPerPage($filter->getDataFilter()->getPerPage());

                return;
            }
        }

//        throw new DatasheetBuildException('Failed to set pagination data from PaginationDatasheetFilter');
    }
}
