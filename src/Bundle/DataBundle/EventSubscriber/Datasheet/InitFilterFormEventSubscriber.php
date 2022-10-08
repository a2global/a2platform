<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Manager\DatasheetParametersManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitFilterFormEventSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'all';

    public function __construct(
        protected DatasheetParametersManager $datasheetParametersManager,
    ) {
    }

    public function initFilterForm(OnDatasheetBuildEvent $event)
    {
        $this->datasheetParametersManager->addEmptyFiltersForm($event->getDatasheet());
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['initFilterForm', 800],
        ];
    }
}