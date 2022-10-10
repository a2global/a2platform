<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event;

use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;

class OnEntityListDatasheetBuild
{
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