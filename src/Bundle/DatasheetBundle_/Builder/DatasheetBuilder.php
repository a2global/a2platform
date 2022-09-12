<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Builder;

use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnAfterBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnColumnsBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DatasheetBuilder
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function build(Datasheet $datasheet): DatasheetExposed
    {
        $datasheet = $this->expose($datasheet);
        $this->eventDispatcher->dispatch(new OnDataBuildEvent($datasheet));

        if (is_null($datasheet->getData())) {
            throw new DatasheetBuildException('Data should be set for the datasheet');
        }

        $this->eventDispatcher->dispatch(new OnColumnsBuildEvent($datasheet));

        if (is_null($datasheet->getColumns())) {
            throw new DatasheetBuildException('Columns should be set for the datasheet');
        }

        $this->eventDispatcher->dispatch(new OnAfterBuildEvent($datasheet));

        return $datasheet;
    }

    protected function expose(Datasheet $datasheet): DatasheetExposed
    {
        $config = $datasheet();
        $datasheet = new DatasheetExposed();
        $datasheet
            ->setId($config['id'] ?? substr(md5($config['invokedAt']), 0, 5))
            ->setConfig($config);

        if (isset($config['title'])) {
            $datasheet->setTitle($config['title']);
        }

        return $datasheet;
    }
}