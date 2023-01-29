<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu;

class MenuItem
{
    protected string $url;

    public function __construct(
        protected string $text
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}