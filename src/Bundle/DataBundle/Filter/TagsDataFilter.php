<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TagsDataFilter extends AbstractDataFilter
{
    const NAME = 'tags';

    protected string $tags = '';

    public function getTags(): string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function buildForm(FormBuilderInterface $container): self
    {
        $container->add('tags', TextType::class, [
            'data' => $this->tags,
            'required' => false,
        ]);

        return $this;
    }
}