<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Reader;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Event\DataReader\OnQueryBuilderFieldsBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Filter\PaginationDataFilter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QueryBuilderDataReader extends AbstractDataReader implements DataReaderInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function supports($source): bool
    {
        return $source instanceof QueryBuilder;
    }

    public function getSource(): mixed
    {
        return $this->source;
    }

    public function readData(): DataCollection
    {
        /** @var QueryBuilder $originalQueryBuilder */
        $queryBuilder = $this->source;
        $buildFieldsEvent = new OnQueryBuilderFieldsBuildEvent($queryBuilder);
        $this->eventDispatcher->dispatch($buildFieldsEvent);
        $collection = new DataCollection($buildFieldsEvent->getFields());
        $this->applyFilters([], [PaginationDataFilter::class]);
        $this->setItemsTotal($collection);
        $this->applyFilters([PaginationDataFilter::class]);
//        $sql = $queryBuilder->getQuery()->getSql();
//        $params = $queryBuilder->getQuery()->getParameters();
//
        foreach ($queryBuilder->getQuery()->getResult() as $item) {
            $collection->addItem(new DataItem($item));
        }

        return $collection;
    }

    protected function setItemsTotal(DataCollection $collection)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = clone($this->getSource());
        $queryBuilder
            ->resetDQLPart('select')
            ->addSelect(sprintf('COUNT(%s.id) AS total', QueryBuilderUtility::getPrimaryAlias($queryBuilder)))
            ->setFirstResult(null)
            ->setMaxResults(null);

        $collection->setItemsTotal($queryBuilder->getQuery()->getSingleScalarResult());
    }

//    public function getFields()
//    {
//        return $this->fields;
//    }
//
//    public function setFields($fields): self
//    {
//        $this->fields = $fields;
//        return $this;
//    }

//    protected function getFields(QueryBuilder $queryBuilder): array
//    {
//        return array_map(function ($field) {
//            return $field['name'];
//        }, QueryBuilderUtility::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder)));
//    }
//
//
}