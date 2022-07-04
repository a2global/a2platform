<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Component;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;
use Exception;

class DatasheetExposed
{
    protected ?string $id;

    /** @var DatasheetColumn[] */
    protected array $columns = [];

    /** @var DatasheetColumn[] */
    protected array $sortedColumns = [];

    protected ?string $title = null;

    protected $data;

    protected array $queryParameters;

    public function __construct()
    {
    }

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

    public function getData(): DataCollection
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

    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    public function setQueryParameters($queryParameters): void
    {
        $this->queryParameters = $queryParameters;
    }

//    protected function buildId()
//    {
//    }

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