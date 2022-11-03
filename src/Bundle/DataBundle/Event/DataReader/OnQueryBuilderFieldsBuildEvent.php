<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\DataReader;

use Doctrine\ORM\QueryBuilder;

class OnQueryBuilderFieldsBuildEvent
{
    protected $fields;

    public function __construct(
        protected QueryBuilder $queryBuilder
    ) {
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields): self
    {
        $this->fields = $fields;
        return $this;
    }
}