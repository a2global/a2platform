<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use DateTime;

class FieldInDateIntervalFilter implements FilterInterface
{
    public function __construct(
        protected $fieldName,
        protected DateTime $dateFrom,
        protected DateTime $dateTo,
    ) {
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }

    public function getDateFrom(): DateTime
    {
        return $this->dateFrom;
    }

    public function getDateTo(): DateTime
    {
        return $this->dateTo;
    }
}