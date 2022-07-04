<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

class FieldContainsFilter implements FilterInterface
{
    public function __construct(
        protected $fieldName,
        protected $containsValue
    ) {
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    public function getContainsValue()
    {
        return $this->containsValue;
    }
}