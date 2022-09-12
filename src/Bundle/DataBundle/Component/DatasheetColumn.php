<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataType\DataTypeInterface;

class DatasheetColumn
{
    public const TEXT_ALIGN_LEFT = 'left';
    public const TEXT_ALIGN_RIGHT = 'right';

    protected string $align = self::TEXT_ALIGN_LEFT;

    protected int $width = 100;

    protected array $filters = [];

    public function __construct(
        protected DataTypeInterface $dataType,
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
        return $this->title ?? StringUtility::normalize($this->name);
    }

    public function getAlign(): string
    {
        return $this->align;
    }
//
//    public function setAlign(string $align): self
//    {
//        $this->align = $align;
//        return $this;
//    }
//
    public function getWidth(): int
    {
        return $this->width;
    }
//
//    public function setWidth(int $width): self
//    {
//        $this->width = $width;
//        return $this;
//    }
//
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
    public function getReadableView(DataItem $dataItem)
    {
        $value = $dataItem->getValue($this->getName());

        return $this->dataType::getReadablePreview($value);
    }
}