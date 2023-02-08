<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Data\DataCollection;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Data\DataItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\PaginationDataFilter;

class ArrayDataReader extends AbstractDataReader implements DataReaderInterface
{
    public function supports($data): bool
    {
        return is_array($data);
    }

    public function getFields(): array
    {
        $firstItem = array_slice($this->source, 0, 1);
        $firstItem = reset($firstItem);

        return array_keys($firstItem);
    }

    public function readData(): DataCollection
    {
        $collection = new DataCollection($this->getFields());
        $this->applyFilters([], [PaginationDataFilter::class]);
        $collection->setItemsTotal(count($this->source));
        $this->applyFilters([PaginationDataFilter::class]);

        foreach ($this->source as $row) {
            $collection->addItem(new DataItem($row));
        }

        return $collection;
    }
}