<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderDataReader extends AbstractDataReader implements DataReaderInterface
{
    public function supports($source): bool
    {
        return $source instanceof QueryBuilder;
    }

    public function getData(): DataCollection
    {
        /** @var QueryBuilder $originalQueryBuilder */
        $queryBuilder = $this->source;
        $collection = new DataCollection($this->getFields($queryBuilder));
        $this->applyFilters();
        $sql = $queryBuilder->getQuery()->getSql();
        $params = $queryBuilder->getQuery()->getParameters();

        foreach ($queryBuilder->getQuery()->getResult() as $object) {
            $collection->addItem(new DataItem($object));
        }

        return $collection;
    }

    public function getItemsTotal(): int
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = clone($this->source);
        $queryBuilder
            ->resetDQLPart('select')
            ->addSelect(sprintf('COUNT(%s.id) AS total', QueryBuilderUtility::getPrimaryAlias($queryBuilder)))
            ->setFirstResult(null)
            ->setMaxResults(null);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    protected function getFields(QueryBuilder $queryBuilder): array
    {
        return array_map(function ($field) {
            return $field['name'];
        }, QueryBuilderUtility::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder)));
    }
}