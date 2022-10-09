<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import\Strategy;

abstract class AbstractImportStrategy implements ImportStrategyInterface
{
    const NAME = null;

    public function getName(): string
    {
        return static::NAME;
    }

    protected function createNewObject($entity)
    {
        return new $entity;
    }
}