<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet\QueryBuilderColumnBuild;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\IntegerDataType;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnQueryBuilderDatasheetColumnBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IdentifierColumnBuildSubscriber implements EventSubscriberInterface
{
    public function buildColumn(OnQueryBuilderDatasheetColumnBuildEvent $event)
    {
        if (!in_array($event->getFieldType(), [
            'int',
            'integer',
        ])) {
            return;
        }

        if (mb_strtolower($event->getFieldName()) !== 'id') {
            return;
        }

        $column = (new DatasheetColumn(new IntegerDataType(), $event->getFieldName()))
            ->setWidth(30)
            ->setAlign(DatasheetColumn::TEXT_ALIGN_RIGHT);
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