<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

abstract class AbstractDataReader
{
    protected $filters = [];

    protected $data;

    public function setSource($data): DataReaderInterface
    {
        $this->data = $data;

        return $this;
    }

    public function addFilter($filter): DataReaderInterface
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}