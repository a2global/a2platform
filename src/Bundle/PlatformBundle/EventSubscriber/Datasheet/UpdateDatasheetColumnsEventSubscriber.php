<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetColumn;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\ObjectDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet\OnDatasheetBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdateDatasheetColumnsEventSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'all';

    private const COLUMN_PARAMETERS_DEFAULTS = [
        'title' => null,
        'width' => 200,
        'align' => DatasheetColumn::TEXT_ALIGN_LEFT,
        'link' => null,
        'type' => ObjectDataType::class,
        'bold' => false,
    ];

    public function updateColumns(OnDatasheetBuildEvent $event)
    {
        foreach ($event->getDatasheet()->getColumnsToUpdate() as $name => $predefinedColumn) {
            if (is_null($predefinedColumn)) {
                $event->getDatasheet()->removeColumn($name);

                continue;
            }
            $datasheetColumn = $event->getDatasheet()->getColumnByName($name);
            $this->updateWithPredefinedColumnParameters($datasheetColumn, $predefinedColumn);
        }

        foreach ($event->getDatasheet()->getColumns() as $column) {
            $this->updateWithDefaultParameters($column);
        }
    }

    protected function updateWithPredefinedColumnParameters(
        DatasheetColumn $datasheetColumn,
        DatasheetColumn $predefinedColumn
    ) {
        foreach (self::COLUMN_PARAMETERS_DEFAULTS as $paramName => $defaultValue) {
            $value = ObjectHelper::getProperty($predefinedColumn, $paramName);

            if (is_null($value)) {
                continue;
            }
            ObjectHelper::setProperty($datasheetColumn, $paramName, $value);
        }
    }

    protected function updateWithDefaultParameters(DatasheetColumn $datasheetColumn)
    {
        foreach (self::COLUMN_PARAMETERS_DEFAULTS as $paramName => $defaultValue) {
            $value = ObjectHelper::getProperty($datasheetColumn, $paramName);

            if (!is_null($value) || is_null($defaultValue)) {
                continue;
            }
            ObjectHelper::setProperty($datasheetColumn, $paramName, $defaultValue);
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['updateColumns', 100],
        ];
    }
}