<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet;

class Datasheet
{
    protected string $invokedAt;

    protected array $columns = [];

    protected array $controls = [];

    public function __construct(
        protected mixed   $datasource,
        protected ?string $title = null,
        protected ?string $id = null,
        protected ?array  $massActions = [],
    ) {
        $backtrace = debug_backtrace();
        $this->invokedAt = sprintf('%s:%s', $backtrace[1]['class'], $backtrace[1]['line']);
    }

    public function __invoke(): array
    {
        return [
            'datasource' => $this->datasource,
            'title' => $this->title,
            'id' => $this->id,
            'invokedAt' => $this->invokedAt,
            'columnsToUpdate' => $this->columns,
            'controls' => $this->controls,
            'massActions' => $this->massActions,
        ];
    }

    public function getColumn($fieldName): DatasheetColumn
    {
        if (!isset($this->columns[$fieldName])) {
            $this->columns[$fieldName] = new DatasheetColumn($fieldName);
        }

        return $this->columns[$fieldName];
    }

    public function addControl($text, $url)
    {
        $this->controls[$text] = $url;
    }

    public function setMassActions(array $actions): self
    {
        $this->massActions = $actions;

        return $this;
    }

    public function hideColumns(...$names): self
    {
        foreach ($names as $name) {
            $this->columns[$name] = null;
        }

        return $this;
    }
}