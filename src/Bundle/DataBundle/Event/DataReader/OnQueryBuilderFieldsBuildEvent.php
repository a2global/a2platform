<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\DataReader;

use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;

class OnQueryBuilderFieldsBuildEvent
{
    public function __construct(
        protected QueryBuilderDataReader $queryBuilderDataReader
    ) {
    }

    public function getQueryBuilderDataReader(): QueryBuilderDataReader
    {
        return $this->queryBuilderDataReader;
    }
}