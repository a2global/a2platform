<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

class Action
{
    protected string $name;

    protected string $routeName;

    protected array $routeParameters = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getRouteName(): string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): self
    {
        $this->routeName = $routeName;
        return $this;
    }

    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    public function setRouteParameters(array $routeParameters): self
    {
        $this->routeParameters = $routeParameters;
        return $this;
    }
}