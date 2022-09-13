<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class DatasheetFilterFormBuilder
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
    ) {
    }

    public function buildDatasheetFilterForm(DatasheetExposed $datasheet): FormInterface
    {
        $builder = $this->formFactory
            ->createNamedBuilder('datasheet')
            ->add($datasheet->getFilterFormBuilder());

        return $builder->getForm();
    }
}