<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet\QueryBuilderColumnBuild;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\StringDataType;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnQueryBuilderDatasheetColumnBuildEvent;
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
            ->setType(new StringDataType());
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