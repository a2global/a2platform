<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Registry;

use Exception;

abstract class AbstractRegistry
{
    public function __construct(
        protected $services
    ) {
    }

    public function get()
    {
        return $this->services;
    }

    public function find(string $className)
    {
        foreach ($this->services as $service) {
            if (get_class($service) === $className) {
                return $service;
            }
        }

        throw new Exception(
            sprintf('Service not found by classname `%s` in `%s`', $className, __CLASS__)
        );
    }
}