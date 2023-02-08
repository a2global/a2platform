<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventSubscriber\Datasheet\QueryBuilderColumnBuild;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetColumn;
use A2Global\A2Platform\Bundle\PlatformBundle\DataType\BooleanDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet\OnQueryBuilderDatasheetColumnBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BooleanColumnBuildSubscriber implements EventSubscriberInterface
{
    public function buildColumn(OnQueryBuilderDatasheetColumnBuildEvent $event)
    {
        if (!in_array($event->getFieldType(), [
            'boolean',
        ])) {
            return;
        }
        $column = (new DatasheetColumn($event->getFieldName()))
            ->setType(new BooleanDataType());
        $event->setColumn($column);
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