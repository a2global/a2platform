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

        foreach ($this->data as $row) {
            if ($this->getFilters() && !$this->passFilters($row)) {
                continue;
            }
            if (!$collection) {
                $collection = new DataCollection(array_keys($row));
            }
            $collection->addItem(new DataItem($row));
        }

        return $collection;
    }

    protected function passFilters($row)
    {
        foreach ($this->getFilters() as $filter) {
            if ($filter instanceof FieldContainsFilter) {
                if (!isset($row[$filter->getFieldName()])) {
                    throw new Exception('Invalid filter: field not found: ' . $filter->getFieldName());
                }

                if (!stristr($row[$filter->getFieldName()], $filter->getContainsValue())) {
                    return false;
                }
            }
        }

        return true;
    }
}