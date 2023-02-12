<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventSubscriber\Datasheet\AddColumnFilterSubscriber;

use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\DateDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\DateTimeDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\FloatDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\IntegerDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\StringDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Datasheet\OnDatasheetColumnFiltersBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\StringContainsDataFilter;
use A2Global\A2Platform\Bundle\PlatformBundle\Manager\DatasheetParametersManager;
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