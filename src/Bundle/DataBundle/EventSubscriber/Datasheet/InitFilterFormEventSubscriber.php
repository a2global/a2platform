<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;

class InitFilterFormEventSubscriber implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'all';

    public function __construct(
        protected FormFactoryInterface $formFactory,
    ) {
    }

    public function initFilterForm(OnDatasheetBuildEvent $event)
    {
        $builder = $this->formFactory
            ->createNamedBuilder($event->getDatasheet()->getId())
            ->add('datasheet', null, ['compound' => true])
            ->add('column', null, ['compound' => true]);
        $event->getDatasheet()->setFilterFormBuilder($builder);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['initFilterForm', 890],
        ];
    }
}