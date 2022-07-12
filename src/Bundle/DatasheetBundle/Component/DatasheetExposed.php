<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;
use Exception;

class DatasheetExposed implements DatasheetInterface
{
    protected ?string $id;

    /** @var DatasheetColumn[] */
    protected array $columns = [];

    /** @var DatasheetColumn[] */
    protected array $sortedColumns = [];

    protected ?string $title = null;

    protected ?array $config = [];

    protected ?DataCollection $data = null;

    protected ?array $filters = [];

    protected ?int $itemsTotal = 1;

    protected ?int $page = 1;

    protected ?int $perPage = 1;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setData(DataCollection $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): ?DataCollection
    {
        return $this->data;
    }

    public function addColumn(DatasheetColumn $column)
    {
        $this->columns[] = $column;
    }

    public function getColumns()
    {
        if (empty($this->sortedColumns)) {
            $this->buildSortedColumns();
        }

        return $this->sortedColumns;
    }

    public function getSortedColumns(): array
    {
        return $this->sortedColumns;
    }

    public function setSortedColumns(array $sortedColumns): self
    {
        $this->sortedColumns = $sortedColumns;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    public function getFilters(): ?array
    {
        return $this->filters;
    }

    public function addFilter(FilterInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getItemsTotal(): ?int
    {
        return $this->itemsTotal;
    }

    public function setItemsTotal(?int $itemsTotal): self
    {
        $this->itemsTotal = $itemsTotal;
        return $this;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(?int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    public function setPerPage(?int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function getPagesTotal(): int
    {
        return floor($this->itemsTotal / $this->perPage);
    }

    protected function buildSortedColumns()
    {
        $columnsWithPosition = [];
        $columnsWithoutPosition = [];

        foreach ($this->columns as $column) {
            if (!$column->getPosition()) {
                $columnsWithoutPosition[] = $column;

                continue;
            }

            if (array_key_exists($column->getPosition(), $columnsWithPosition)) {
                throw new DatasheetBuildException(
                    sprintf(
                        'Few columns with same position found: %s, %s',
                        $columnsWithPosition[$column->getPosition()]->getName(),
                        $column->getName(),
                    )
                );
            }
            $columnsWithPosition[$column->getPosition()] = $column;
        }
        $sortedColumns = [];

        for ($i = 0; $i < count($this->columns); $i++) {
            if (array_key_exists($i, $columnsWithPosition)) {
                $sortedColumns[] = $columnsWithPosition[$i];

                continue;
            }
            $nextColumn = array_splice($columnsWithoutPosition, 0, 1);
            $sortedColumns[] = reset($nextColumn);
        }

        $this->sortedColumns = $sortedColumns;
    }
}