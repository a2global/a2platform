<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Filter;

class PaginationFilter implements FilterInterface
{
    public function __construct(
        protected int $page,
        protected int $limit,
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

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
}