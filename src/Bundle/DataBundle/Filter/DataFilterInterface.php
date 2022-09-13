<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use Symfony\Component\Form\FormBuilderInterface;

interface DataFilterInterface
{
    public function addToForm(FormBuilderInterface $container): self;
}