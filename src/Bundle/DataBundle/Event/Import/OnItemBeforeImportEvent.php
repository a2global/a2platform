<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\Import;

class OnItemBeforeImportEvent
{
    public function __construct(
        protected string $entity,
        protected array $data
    ) {
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}