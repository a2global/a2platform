<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class PaginationDataFilter extends AbstractDataFilter
{
    const NAME = 'pagination';

    protected int $page = 1;

    protected int $limit = 20;

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function buildForm(FormBuilderInterface $container): self
    {
        $container->add('page', IntegerType::class, [
            'data' => $this->page,
            'required' => false,
        ]);
        $container->add('limit', IntegerType::class, [
            'data' => $this->limit,
            'required' => false,
        ]);

        return $this;
    }
}