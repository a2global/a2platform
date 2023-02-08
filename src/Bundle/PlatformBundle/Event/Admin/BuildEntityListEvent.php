<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Event\Admin;


use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\Datasheet;

class BuildEntityListEvent
{
    public const NAME = 'a2platform.admin.entity.list.build';

    protected ?Datasheet $datasheet = null;

    public function __construct(
        protected string $entityClassName
    ) {
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    public function getDatasheet(): ?Datasheet
    {
        return $this->datasheet;
    }

    public function setDatasheet(?Datasheet $datasheet): self
    {
        $this->datasheet = $datasheet;
        return $this;
    }
}