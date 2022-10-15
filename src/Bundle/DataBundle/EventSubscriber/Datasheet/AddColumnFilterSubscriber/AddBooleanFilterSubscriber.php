<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet\AddColumnFilterSubscriber;

use A2Global\A2Platform\Bundle\DataBundle\DataType\BooleanDataType;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetColumnFiltersBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Filter\BooleanDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\StringContainsDataFilter;
use A2Global\A2Platform\Bundle\DataBundle\Manager\DatasheetParametersManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddBooleanFilterSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected DatasheetParametersManager $parametersManager,
    ) {
    }

    public function addFilter(OnDatasheetColumnFiltersBuildEvent $event)
    {
        if (!in_array(get_class($event->getColumn()->getType()), [
            BooleanDataType::class,
        ])) {
            return;
        }
        $parameters = $this->parametersManager->getDatasheetFilterParameters(
            $event->getDatasheet(),
            BooleanDataFilter::getName(),
            $event->getColumn()->getName(),
        );
        $filter = new BooleanDataFilter();
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