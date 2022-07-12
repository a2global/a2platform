<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\FilterApplier\FilterApplierInterface;
use A2Global\A2Platform\Bundle\DataBundle\Registry\FilterApplierRegistry;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;

abstract class AbstractDataReader
{
    /** @var FilterInterface[] */
    protected $filters = [];

    protected $source;

    protected int $itemsTotal;

    protected FilterApplierRegistry $filterApplierRegistry;

    public function getSource(): mixed
    {
        return $this->source;
    }

    public function setSource($source): DataReaderInterface
    {
        $this->source = $source;

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

    public function getItemsTotal(): int
    {
        return $this->itemsTotal;
    }

    /** @Required */
    public function setFilterApplierRegistry(FilterApplierRegistry $filterApplierRegistry)
    {
        $this->filterApplierRegistry = $filterApplierRegistry;
    }

    protected function applyFilters($paginationFilter = false)
    {
        /** @var FilterInterface $filter */
        foreach ($this->filters as $filter) {
            if ($paginationFilter && !$filter instanceof PaginationFilter) {
                continue;
            }

            if (!$paginationFilter && $filter instanceof PaginationFilter) {
                continue;
            }
            $filterApplied = false;

            /** @var FilterApplierInterface $filterApplier */
            foreach ($this->filterApplierRegistry->get() as $filterApplier) {
                if ($filterApplied || !$filterApplier->supports($this, $filter)) {
                    continue;
                }
                $filterApplier->apply($this, $filter);
                $filterApplied = true;
            }

            if (!$filterApplied) {
                throw new DatasheetBuildException('Filter not aplied: ' . get_class($filter));
            }
        }
    }
}