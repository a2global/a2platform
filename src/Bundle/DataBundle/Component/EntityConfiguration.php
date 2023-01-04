<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

use Exception;

class EntityConfiguration
{
    /** @var Action[] */
    protected array $actions = [];

    protected array $massActions = [];

    protected array $sidebarTabs = [];

    protected string $defaultAction;

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

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function addAction(Action $entityAction): self
    {
        $this->actions[] = $entityAction;

        return $this;
    }

    public function removeAction(string $name)
    {
        for ($i = 0; $i < count($this->actions); $i++) {
            if ($this->actions[$i]->getName() === $name) {
                unset($this->actions[$i]);

                return;
            }
        }

        throw new Exception('Failed to find action by name: ' . $name);
    }

    /**
     * @return Action[]
     */
    public function getMassActions(): array
    {
        return $this->massActions;
    }

    public function addMassAction(Action $entityAction): self
    {
        $this->massActions[] = $entityAction;

        return $this;
    }

    public function addSidebarTab(string $name, $contentSource): self
    {
        $this->sidebarTabs[$name] = $contentSource;

        return $this;
    }

    public function getSidebarTabs(): array
    {
        return $this->sidebarTabs;
    }

    public function getDefaultAction(): string
    {
        return $this->defaultAction;
    }

    public function setDefaultAction(string $defaultAction): self
    {
        $this->defaultAction = $defaultAction;
        return $this;
    }
}