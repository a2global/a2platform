<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Component\EntityConfiguration;
use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class EntityConfigurationBuilder
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function build($object): EntityConfiguration
    {
        $entityConfiguration = new EntityConfiguration($object);
        $this->eventDispatcher->dispatch(new EntityConfigurationBuildEvent($entityConfiguration));

        return $entityConfiguration;
    }
}