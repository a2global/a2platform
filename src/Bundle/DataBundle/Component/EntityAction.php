<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

class EntityAction
{
    protected string $name;

    protected string $url;

    protected bool $isDefault = false;

    protected ?string $title = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
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

    public function getTitle(): ?string
    {
        return $this->title ?? $this->name;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }
}