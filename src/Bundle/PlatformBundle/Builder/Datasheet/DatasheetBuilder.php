<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Datasheet;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\Datasheet;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetExposed;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
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
        $this->eventDispatcher
            ->dispatch(new OnDatasheetBuildEvent($datasheet));

        return $datasheet;
    }

    protected function expose(Datasheet $datasheet): DatasheetExposed
    {
        $parameters = $datasheet();
        $id = $parameters['id']
            ? StringUtility::toSnakeCase($parameters['id'])
            : substr(md5($parameters['invokedAt']), 0, 5);

        return new DatasheetExposed(
            $parameters['datasource'],
            $id,
            $parameters['title'],
            $parameters['columnsToUpdate'],
            $parameters['controls'],
            $parameters['massActions'],
        );
    }
}