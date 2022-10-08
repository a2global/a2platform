<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractDataFilter implements DataFilterInterface
{
    const NAME = null;

    protected $value = null;

    public static function getName(): string
    {
        return static::NAME;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEnabled(): bool
    {
        return !empty($this->value);
    }

    public function buildForm(FormBuilderInterface $container): self
    {
        $container->add('value', TextType::class, [
            'data' => $this->value,
            'required' => false,
        ]);

        return $this;
    }
}