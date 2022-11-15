<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

class EntityConfiguration
{
    protected array $actions = [];

    protected array $sidebarTabs = [];

    public function __construct(
        protected object $object
    ) {
    }

    public function getObject(): object
    {
        return $this->object;
    }

    public function getClassname(): string
    {
        return get_class($this->object);
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function addAction(EntityAction $entityAction): self
    {
        $this->actions[] = $entityAction;

        return $this;
    }

    public function addSidebarTab(string $name, callable $contentSource): self
    {
        $this->sidebarTabs[$name] = $contentSource;

        return $this;
    }
}