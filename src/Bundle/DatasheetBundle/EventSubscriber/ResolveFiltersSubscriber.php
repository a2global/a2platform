<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnAddFilterEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnFindFiltersEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Filter\DatasheetFilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Registry\DatasheetFilterRegistry;
use A2Global\A2Platform\Bundle\DatasheetBundle\Resolver\FilterResolver;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

class ResolveFiltersSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected DatasheetFilterRegistry $datasheetFilterRegistry,
        protected RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['resolveFilters', 470],
        ];
    }

    public function resolveFilters(OnDataBuildEvent $event)
    {
        $datasheetParameters = $this->requestStack
            ->getCurrentRequest()
            ->query
            ->get(sprintf('ds%s', $event->getDatasheet()->getId()), []);

        foreach ($datasheetParameters['filter'] ?? [] as $filterParameters) {
            $filterParameters = new ParameterBag($filterParameters);

            /** @var DatasheetFilterInterface $filter */
            foreach ($this->datasheetFilterRegistry->get() as $filter) {

                if ($filter->isDefined($filterParameters)) {
                    $event->getDatasheet()->addFilter($filter->get($filterParameters));
                }
            }
        }
    }
}