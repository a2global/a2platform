<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

class PaginationFilter implements FilterInterface
{
    public function __construct(
        protected int $page,
        protected int $perPage,
    ) {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }
}