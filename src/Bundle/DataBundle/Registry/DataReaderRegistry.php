<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Registry;

use A2Global\A2Platform\Bundle\CoreBundle\Registry\AbstractRegistry;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use Exception;

class DataReaderRegistry extends AbstractRegistry
{
    public function findDataReader($source): DataReaderInterface
    {
        /** @var DataReaderInterface $dataReader */
        foreach ($this->get() as $dataReader) {
            if ($dataReader->supports($source)) {
                return $dataReader->setSource($source);
            }
        }

        throw new Exception('Failed to find proper data reader'); // @codeCoverageIgnore
    }
}