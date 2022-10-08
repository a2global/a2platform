<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DatasheetBuilder
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function buildDatasheet(Datasheet $datasheet): DatasheetExposed
    {
        $datasheet = $this->expose($datasheet);
        $this->eventDispatcher->dispatch(new OnDatasheetBuildEvent($datasheet));

        return $datasheet;
    }

    protected function expose(Datasheet $datasheet): DatasheetExposed
    {
        $parameters = $datasheet();

        return new DatasheetExposed(
            $parameters['datasource'],
            $parameters['id'] ?? substr(md5($parameters['invokedAt']), 0, 5),
            $parameters['title'],
            $parameters['columnsToUpdate'],
            $parameters['controls'],
        );
    }
}