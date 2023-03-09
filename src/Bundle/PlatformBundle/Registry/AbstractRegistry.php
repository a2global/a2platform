<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Registry;

use Exception;

abstract class AbstractRegistry
{
    public function __construct(
        protected iterable $services,
    ) {
    }

    public function get(): iterable
    {
        return $this->services;
    }

    public function findSupporting(...$parameters): array
    {
        $supporting = [];

        foreach ($this->services as $service) {
            if ($service->supports(...$parameters)) {
                $supporting[] = $service;
            }
        }

        return $supporting;
    }

    public function findByClassname(string $className): mixed
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