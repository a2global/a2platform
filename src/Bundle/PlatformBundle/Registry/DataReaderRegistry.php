<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Registry;

use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\DataReaderInterface;
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