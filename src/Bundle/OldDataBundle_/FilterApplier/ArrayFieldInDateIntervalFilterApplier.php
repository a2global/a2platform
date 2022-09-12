<?php

namespace A2Global\A2Platform\Bundle\DataBundle\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Filter\ContainsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldBooleanFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldEqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldExactDateFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FieldInDateIntervalFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationFilter;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use Throwable;

class ArrayFieldInDateIntervalFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, FilterInterface $filter): bool
    {
        return $dataReader instanceof ArrayDataReader && $filter instanceof FieldInDateIntervalFilter;
    }

    public function apply(DataReaderInterface $dataReader, FilterInterface $filter)
    {
        /** @var FieldInDateIntervalFilter $filter */
        $filteredItems = [];
        $from = $filter->getDateFrom();
        $to = $filter->getDateTo();

        foreach ($dataReader->getSource() as $item) {
            $value = $item[$filter->getFieldName()];

            if (empty($value)) {
                continue;
            }

            if (!$value instanceof DateTime) {
                try {
                    $date = new DateTime($value);
                } catch (Throwable $exception) {
                    continue;
                }
            }
            $value->setTime(0,0,0);

            if ($value < $from || $value > $to) {
                continue;
            }
            $filteredItems[] = $item;
        }
        $dataReader->setSource($filteredItems);
    }
}