<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet\QueryBuilderColumnBuild;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\FloatDataType;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\IntegerDataType;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\StringDataType;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnQueryBuilderDatasheetColumnBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FloatColumnBuildSubscriber implements EventSubscriberInterface
{
    public function buildColumn(OnQueryBuilderDatasheetColumnBuildEvent $event)
    {
        if (!in_array($event->getFieldType(), [
            'float',
            'decimal',
        ])) {
            return;
        }

        $event->setColumn(
            new DatasheetColumn(
                new FloatDataType(),
                $event->getFieldName(),
            )
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnQueryBuilderDatasheetColumnBuildEvent::class => ['buildColumn'],
        ];
    }
}