<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DataTypeInterface;
use A2Global\A2Platform\Bundle\DataBundle\DataType\IntegerType;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;

class DatasheetColumn implements DatasheetColumnInterface
{
    public const ALIGN_LEFT = 'left';
    public const ALIGN_CENTER = 'center';
    public const ALIGN_RIGHT = 'right';

    protected ?int $width = null;

    protected ?int $position = null;

    protected ?string $title = null;

    protected ?string $type = null;

    protected ?string $align = null;

    protected ?bool $filterable = null;

    public function __construct(
        protected $name
    ) {
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle(): ?string
    {
        return $this->title ?? StringUtility::normalize($this->name);
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
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

    public function isFilterable(): ?bool
    {
        return $this->filterable;
    }

    public function setFilterable(bool $filterable): self
    {
        $this->filterable = $filterable;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getView(DataItem $dataItem): ?string
    {
        throw new DatasheetBuildException('This method should not be called directly, only typed columns should return view');
    }

    public static function supportsDataType($type): bool
    {
        return false;
    }
}