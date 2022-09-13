<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class PaginationDataFilter implements DataFilterInterface
{
    protected int $page = 0;

    protected int $limit = 10;

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

    public function addToForm(FormBuilderInterface $container): self
    {
        $container->add('page', IntegerType::class, [
            'data' => $this->page,
        ]);
        $container->add('limit', IntegerType::class, [
            'data' => $this->limit,
        ]);

        return $this;
    }
}