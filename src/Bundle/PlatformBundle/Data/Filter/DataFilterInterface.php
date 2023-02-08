<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter;

use Symfony\Component\Form\FormBuilderInterface;

interface DataFilterInterface
{
    public static function getName(): string;

    public function isEnabled(): bool;

    public function buildForm(FormBuilderInterface $container): self;
}