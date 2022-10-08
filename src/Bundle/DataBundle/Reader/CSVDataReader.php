<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;

class CSVDataReader extends AbstractDataReader implements DataReaderInterface
{
    public function supports($data): bool
    {
        return is_string($data) && !empty($data) && file_exists($data);
    }

    public function getFields(): array
    {
        $handle = fopen($this->source, 'r');
        $fileFields = fgetcsv($handle);
        fclose($handle);

        return $fileFields;
    }

    public function readData(): DataCollection
    {
        $firstRowSkipped = false;
        $collection = new DataCollection($this->getFields());
        $i = 0;

        foreach (file($this->source) as $line) {
            if (!$firstRowSkipped) {
                $firstRowSkipped = true;

                continue;
            }
            $row = str_getcsv($line);
            $collection->addItem(new DataItem($row));
            $i++;
        }
        $collection->setItemsTotal($i);

        return $collection;
    }
}