<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

class SearchFilter implements FilterInterface
{
    public function __construct(
        protected $query,
    ) {
    }

    public function getQuery()
    {
        return $this->query;
    }
}