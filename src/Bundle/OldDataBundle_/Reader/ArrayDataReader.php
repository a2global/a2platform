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
        $firstItem = array_slice($this->source, 0, 1);
        $firstItem = reset($firstItem);
        $collection = new DataCollection(array_keys($firstItem));
        $this->applyFilters();
        $collection->setItemsTotal(count($this->source));
        $this->applyFilters(true);

        foreach ($this->source as $row) {
            $collection->addItem(new DataItem($row));
        }

        return $collection;
    }
}