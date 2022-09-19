<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\DataTypeInterface;

class DatasheetColumn
{
    public const TEXT_ALIGN_LEFT = 'left';
    public const TEXT_ALIGN_RIGHT = 'right';

    protected ?string $align = null;

    protected ?int $width = null;

    protected mixed $link = null;

    protected ?bool $isBold = null;

    protected ?DataTypeInterface $type = null;

//    protected array $filters = [];

    public function __construct(
        protected string  $name,
        protected ?string $title = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getType(): ?DataTypeInterface
    {
        return $this->type;
    }

    public function setType(?DataTypeInterface $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getAlign(): ?string
    {
        return $this->align;
    }

    public function setAlign(string $align): self
    {
        $this->align = $align;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function getLink(): mixed
    {
        return $this->link;
    }

    public function setLink(mixed $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function isBold(): ?bool
    {
        return $this->isBold;
    }

    public function setBold(?bool $isBold): self
    {
        $this->isBold = $isBold;
        return $this;
    }

//    public function getFilters(): array
//    {
//        return $this->filters;
//    }
//
//    public function setFilters(array $filters): self
//    {
//        $this->filters = $filters;
//        return $this;
//    }
    public function getReadableView(DataItem $dataItem): string
    {
        $value = $dataItem->getValue($this->getName());

        if (!$value) {
            return '';
        }

        return $this->type::getReadablePreview($value);
    }
}