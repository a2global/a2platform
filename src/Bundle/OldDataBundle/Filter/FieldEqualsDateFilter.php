<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use DateTime;

class FieldEqualsDateFilter implements FilterInterface
{
    public function __construct(
        protected $fieldName,
        protected DateTime $date,
    ) {
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    public function getDate()
    {
        return $this->date;
    }
}