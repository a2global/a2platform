<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnAfterBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\FilterProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SetQueryStringParamsSubscriber// implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnAfterBuildEvent::class => ['setQueryStringParams', 500],
        ];
    }

    public function __construct(
        protected RequestStack $requestStack
    ) {
    }

    public function setQueryStringParams(OnAfterBuildEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $queryParams = FilterProvider::decapsulate($request->query, $event->getDatasheet()->getId()) ?? [];
        $event->getDatasheet()->setQueryParameters($queryParams);
    }
}
