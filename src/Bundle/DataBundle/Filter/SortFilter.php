<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

class SortFilter implements FilterInterface
{
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