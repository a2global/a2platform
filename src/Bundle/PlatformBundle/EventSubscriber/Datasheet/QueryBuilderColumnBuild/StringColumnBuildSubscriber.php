<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventSubscriber\Datasheet\QueryBuilderColumnBuild;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\DatasheetColumn;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\StringDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet\OnQueryBuilderDatasheetColumnBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StringColumnBuildSubscriber implements EventSubscriberInterface
{
    public function buildColumn(OnQueryBuilderDatasheetColumnBuildEvent $event)
    {
        if (!in_array($event->getFieldType(), [
            'string',
        ])) {
            return;
        }
        $column = (new DatasheetColumn($event->getFieldName()))
            ->setType(new StringDataType())
            ->setWidth(250);
        $event->setColumn($column);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnQueryBuilderDatasheetColumnBuildEvent::class => ['buildColumn', 200],
        ];
    }
}