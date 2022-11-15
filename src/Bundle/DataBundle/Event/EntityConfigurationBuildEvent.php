<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event;

use A2Global\A2Platform\Bundle\DataBundle\Component\EntityConfiguration;

class EntityConfigurationBuildEvent
{
    public function __construct(
        protected EntityConfiguration $configuration
    ) {
    }

    public function getConfiguration(): EntityConfiguration
    {
        return $this->configuration;
    }

    public function getClassname(): string
    {
        return $this->configuration->getClassname();
    }

    public function getObject(): object
    {
        return $this->configuration->getObject();
    }
}