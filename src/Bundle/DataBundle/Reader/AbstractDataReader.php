<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\DataBundle\Exception\DatasheetBuildException;
use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\FilterApplier\FilterApplierInterface;
use A2Global\A2Platform\Bundle\DataBundle\Registry\FilterApplierRegistry;

abstract class AbstractDataReader implements DataReaderInterface
{
    protected mixed $source;

    protected array $filters;

    protected FilterApplierRegistry $filterApplierRegistry;

    public function setSource(mixed $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getSource(): mixed
    {
        return $this->source;
    }

    public function addFilter(DataFilterInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    /** @Required */
    public function setFilterApplierRegistry(FilterApplierRegistry $filterApplierRegistry)
    {
        $this->filterApplierRegistry = $filterApplierRegistry;
    }

    protected function applyFilters($only = [], $exclude = [])
    {
        /** @var DataFilterInterface $filter */
        foreach ($this->filters as $filter) {

            // Only
            if (count($only) && !in_array($filter::class, $only)) {
                continue;
            }

            // Exclude
            if (in_array($filter::class, $exclude)) {
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
                throw new DatasheetBuildException('Filter not applied: ' . get_class($filter));
            }
        }
    }
}