<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Filter;

use A2Global\A2Platform\Bundle\DataBundle\Filter\EqualsFilter;
use A2Global\A2Platform\Bundle\DataBundle\Filter\FilterInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use Doctrine\Common\Annotations\Annotation\Required;
use Twig\Environment;

abstract class AbstractDatasheetFilter
{
    protected Environment $twig;

    /** @Required */
    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getName()
    {
        return static::NAME;
    }

    public function getFilterClass()
    {
        return static::FILTER_CLASS;
    }

    public function viewControl(DatasheetExposed $datasheet, DatasheetColumn $column)
    {
        $prefix = 'ds' . $datasheet->getId();
        $appliedFilter = $this->findAppliedFilter($datasheet, $column);

        return $this->twig->render('@Datasheet/FilterControl/input_text.html.twig', [
            'parametersPrefix' => $prefix,
            'columnName' => $column->getName(),
            'filterType' => static::NAME,
            'filterValue' => $appliedFilter ? $appliedFilter->getValue() : '',
        ]);
    }

    public function findAppliedFilter(DatasheetExposed $datasheet, DatasheetColumn $column)
    {
        /** @var FilterInterface $filter */
        foreach ($datasheet->getFilters() as $filter) {
            if (get_class($filter) != $this->getFilterClass()) {
                continue;
            }

            if ($filter->getFieldName() !== $column->getName()) {
                continue;
            }

            return $filter;
        }
    }
}