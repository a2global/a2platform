<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet;


use A2Global\A2Platform\Bundle\PlatformBundle\Component\Data\DataItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\DataTypeInterface;

class DatasheetColumn
{
    public const TEXT_ALIGN_LEFT = 'left';
    public const TEXT_ALIGN_RIGHT = 'right';

    protected ?string $align = null;

    protected ?string $title = null;

    protected ?int $width = null;

    protected mixed $link = null;

    protected ?bool $isBold = null;

    protected ?DataTypeInterface $type = null;

    public function __construct(
        protected string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setTitle(string $title): ?self
    {
        $this->title = $title;

        return $this;
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

    public function getReadableView(DataItem $dataItem): string
    {
        $value = $dataItem->getValue($this->getName());

        if (!$value) {
            return '';
        }

        return $this->type::getReadablePreview($value);
    }
}