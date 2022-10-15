<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

use Symfony\Component\Form\FormBuilderInterface;

class DatasheetExposed
{
    protected array $columns;

    protected FormBuilderInterface $filterFormBuilder;

    protected DataCollection $data;

    public function __construct(
        protected mixed   $datasource,
        protected string  $id,
        protected ?string $title,
        protected array   $columnsToUpdate,
        protected array   $controls,
    ) {
    }

    public function getDatasource(): mixed
    {
        return $this->datasource;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function getColumnByName(string $name): DatasheetColumn
    {
        /** @var DatasheetColumn $column */
        foreach ($this->columns as $column) {
            if ($column->getName() === $name) {
                return $column;
            }
        }
    }

    public function removeColumn(string $name): self
    {
        /** @var DatasheetColumn $column */
        foreach ($this->columns as $key => $column) {
            if ($column->getName() === $name) {
                unset($this->columns[$key]);

                return $this;
            }
        }
    }

    public function getData(): DataCollection
    {
        return $this->data;
    }

    public function setData(DataCollection $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setFilterFormBuilder(FormBuilderInterface $filterFormBuilder): self
    {
        $this->filterFormBuilder = $filterFormBuilder;

        return $this;
    }

    public function getFilterFormBuilder(): FormBuilderInterface
    {
        return $this->filterFormBuilder;
    }

    public function getColumnsToUpdate(): array
    {
        return $this->columnsToUpdate;
    }

    public function getControls(): array
    {
        return $this->controls;
    }
//    protected ?string $id;
//
//    /** @var DatasheetColumn[] */
//    protected array $columns = [];
//
//    /** @var DatasheetColumn[] */
//    protected array $sortedColumns = [];
//
//    protected ?string $title = null;
//
//    protected ?array $config = [];
//
//    protected ?DataCollection $data = null;
//
//    protected ?array $filters = [];
//
//    protected ?array $filterForms = [];
//
//    protected ?array $columnFilterForms = [];
//
//    public function getId(): ?string
//    {
//        return $this->id;
//    }
//
//    public function setId(?string $id): self
//    {
//        $this->id = $id;
//        return $this;
//    }
//
//    public function getTitle()
//    {
//        return $this->title;
//    }
//
//    public function setTitle($title): self
//    {
//        $this->title = $title;
//        return $this;
//    }
//
//    public function setData(DataCollection $data): self
//    {
//        $this->data = $data;
//
//        return $this;
//    }
//
//    public function getData(): ?DataCollection
//    {
//        return $this->data;
//    }
//
//    public function addColumn(DatasheetColumn $column)
//    {
//        $this->columns[] = $column;
//    }
//
//    public function getColumns()
//    {
//        if (empty($this->sortedColumns)) {
//            $this->buildSortedColumns();
//        }
//
//        return $this->sortedColumns;
//    }
//
//    public function getConfig(): array
//    {
//        return $this->config;
//    }
//
//    public function setConfig(array $config): self
//    {
//        $this->config = $config;
//        return $this;
//    }
//
//    public function addFilter(FilterInterface $filter): self
//    {
//        $this->filters[] = $filter;
//
//        return $this;
//    }
//
//    public function getFilters(): ?array
//    {
//        return $this->filters;
//    }
//
//    // todo: change to FormInterface-way instead of arrays
//    public function addFilterForm(?array $filterForm, ?DatasheetColumnInterface $column = null): self
//    {
//        if ($column) {
//            $this->columnFilterForms[$column->getName()][] = $filterForm;
//        } else {
//            $this->filterForms[] = $filterForm;
//        }
//
//        return $this;
//    }
//
//    public function getFilterForms(?DatasheetColumn $column = null): ?array
//    {
//        if ($column) {
//            return $this->columnFilterForms[$column->getName()] ?? [];
//        }
//
//        return $this->filterForms;
//    }
//
//    public function getItemsTotal()
//    {
//        return $this->data->getItemsTotal();
//    }
//
//    public function hasActionUrl()
//    {
//        return !empty($this->config['actionUrl']);
//    }
//
//    public function getActionUrl(DataItem $dataItem)
//    {
//        return sprintf($this->config['actionUrl'], $dataItem->getValue('id'));
//    }
//
//    protected function buildSortedColumns()
//    {
//        $columnsWithPosition = [];
//        $columnsWithoutPosition = [];
//
//        foreach ($this->columns as $column) {
//            if (!$column->getPosition()) {
//                $columnsWithoutPosition[] = $column;
//
//                continue;
//            }
//
//            if (array_key_exists($column->getPosition(), $columnsWithPosition)) {
//                throw new DatasheetBuildException(
//                    sprintf(
//                        'Few columns with same position found: %s, %s',
//                        $columnsWithPosition[$column->getPosition()]->getName(),
//                        $column->getName(),
//                    )
//                );
//            }
//            $columnsWithPosition[$column->getPosition()] = $column;
//        }
//        $sortedColumns = [];
//
//        for ($i = 0; $i < count($this->columns); $i++) {
//            if (array_key_exists($i, $columnsWithPosition)) {
//                $sortedColumns[] = $columnsWithPosition[$i];
//
//                continue;
//            }
//            $nextColumn = array_splice($columnsWithoutPosition, 0, 1);
//            $sortedColumns[] = reset($nextColumn);
//        }
//
//        $this->sortedColumns = $sortedColumns;
//    }
}