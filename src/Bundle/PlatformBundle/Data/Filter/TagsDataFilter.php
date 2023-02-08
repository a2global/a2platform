<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter;

use A2Global\A2Platform\Bundle\CoreBundle\Form\Type\TextTagsType;
use Symfony\Component\Form\FormBuilderInterface;

class TagsDataFilter extends AbstractDataFilter
{
    const NAME = 'tags';

    public function buildForm(FormBuilderInterface $container): self
    {
        $container->add('value', TextTagsType::class, [
            'data' => $this->value,
            'attr' => [
                'placeholder' => 'Search by tags',
            ]
        ]);

        return $this;
    }
}