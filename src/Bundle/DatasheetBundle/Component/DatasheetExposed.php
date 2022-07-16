<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DatasheetColumnInterface;
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

    protected ?array $columnFilters = [];

    protected ?array $filtersForm = [];

    protected ?array $columnFiltersForm = [];

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

    public function addFilter(FilterInterface $filter, ?string $columnName = null): self
    {
        if ($columnName) {
            $this->columnFilters[$columnName][] = $filter;
        } else {
            $this->filters[] = $filter;
        }

        return $this;
    }

    public function getFilters(): ?array
    {
        return $this->filters;
    }

    public function getColumnFilters($column): ?array
    {
        return $this->columnFilters;
    }

    // todo: change to FormInterface-way instead of arrays
    public function addFilterForm(string $filterName, ?array $fields, ?DatasheetColumnInterface $column = null): self
    {
        $fields = [$filterName => $fields];

        if ($column) {
            $this->columnFiltersForm[$column->getName()] =
                array_merge($this->columnFiltersForm[$column->getName()] ?? [], $fields);
        } else {
            $this->filtersForm =
                array_merge($this->filtersForm ?? [], $fields);
        }

        return $this;
    }

    public function getFiltersForm(?DatasheetColumn $column = null): ?array
    {
        if ($column) {
            return $this->columnFiltersForm[$column->getName()] ?? [];
        }

        return $this->filtersForm;
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