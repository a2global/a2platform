<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu;

use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;

class MenuItem
{
    protected ?string $text = null;

    protected ?string $url = null;

    protected ?string $routeName = null;

    protected array $routeParameters = [];

    protected bool $isDefault = false;

    protected bool $isActive = false;

    protected mixed $isActiveHandler = null;

    public function __construct(
        protected string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getText(): string
    {
        return $this->text ?? StringUtility::toReadable($this->getName());
    }

    public function setText(?string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(?string $routeName): self
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

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsActiveHandler(): mixed
    {
        return $this->isActiveHandler;
    }

    public function setIsActiveHandler(mixed $isActiveHandler): self
    {
        $this->isActiveHandler = $isActiveHandler;
        return $this;
    }
}