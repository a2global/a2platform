<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldContainsFilter;
use Exception;

class ArrayDataReader extends AbstractDataReader implements DataReaderInterface
{
    public function supports($data): bool
    {
        return is_array($data);
    }

    public function getData(): DataCollection
    {
        $collection = null;
        $this->applyFilters();

        foreach ($this->source as $row) {
            if (!$collection) {
                $collection = new DataCollection(array_keys($row));
            }
            $collection->addItem(new DataItem($row));
        }

        return $collection;
    }

    public function getItemsTotal(): int
    {
        return count($this->source);
    }
}