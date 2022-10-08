<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet\AddColumnFilterSubscriber;

use A2Global\A2Platform\Bundle\DataBundle\DataType\BooleanDataType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateDataType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateTimeDataType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\FloatDataType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\IntegerDataType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\StringDataType;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetColumnFiltersBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Filter\StringContainsDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Manager\DatasheetParametersManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddStringContainsFilterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected DatasheetParametersManager $parametersManager,
    ) {
    }

    public function addFilter(OnDatasheetColumnFiltersBuildEvent $event)
    {
        if (!in_array(get_class($event->getColumn()->getType()), [
            IntegerDataType::class,
            StringDataType::class,
            FloatDataType::class,
            DateDataType::class,
            DateTimeDataType::class,
            BooleanDataType::class,
        ])) {
            return;
        }
        $parameters = $this->parametersManager->getDatasheetFilterParameters(
            $event->getDatasheet(),
            StringContainsDataFilter::getName(),
            $event->getColumn()->getName(),
        );
        $filter = new StringContainsDataFilter();
        $this->parametersManager->applyParameters($filter, $parameters);
        $event->getDataReader()->addFieldFilter($event->getColumn()->getName(), $filter);
        $this->parametersManager->addFilterToDatasheetColumn(
            $event->getDatasheet(),
            $event->getColumn(),
            $filter,
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetColumnFiltersBuildEvent::class => ['addFilter', 800],
        ];
    }
}