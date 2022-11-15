<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SortDataFilter extends AbstractDataFilter
{
    const NAME = 'sort';

    const TYPE_ASCENDING = 'ascending';

    const TYPE_DESCENDING = 'descending';

    protected string $fieldName = '';

    protected string $type = self::TYPE_ASCENDING;

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): self
    {
        $this->fieldName = $fieldName;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function isEnabled(): bool
    {
        return !empty($this->fieldName);
    }

    public function buildForm(FormBuilderInterface $container): self
    {
        $container->add('fieldName', TextType::class, [
            'data' => $this->fieldName,
            'required' => false,
        ]);
        $container->add('type', TextType::class, [
            'data' => $this->type,
            'required' => false,
        ]);

        return $this;
    }
}