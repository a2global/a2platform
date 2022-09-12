<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

class FieldContainsFilter implements FilterInterface
{
    public function __construct(
        protected $fieldName,
        protected $query,
    ) {
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    public function getQuery()
    {
        return $this->query;
    }
}