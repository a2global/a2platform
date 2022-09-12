<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

class SortFilter implements FilterInterface
{
    public const ASCENDING = 'ascending';
    public const DESCENDING = 'descending';

    public function __construct(
        protected $by,
        protected $type,
    ) {
    }

    public function getBy()
    {
        return $this->by;
    }

    public function getType()
    {
        return $this->type;
    }
}