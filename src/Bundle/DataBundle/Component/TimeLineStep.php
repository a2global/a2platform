<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

use DateTimeInterface;

class TimeLineStep
{
    protected ?string $name = null;

    protected ?DateTimeInterface $datetime = null;

    protected ?string $content = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDatetime(): ?DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(?DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }
}