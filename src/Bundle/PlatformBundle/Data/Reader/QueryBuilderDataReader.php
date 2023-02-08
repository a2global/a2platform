<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Data\DataCollection;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Data\DataItem;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\DataReader\OnQueryBuilderFieldsBuildEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\PaginationDataFilter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class QueryBuilderDataReader extends AbstractDataReader implements DataReaderInterface
{
    protected $fields;

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

    public function getFields(): array
    {
        if (is_null($this->fields)) {
            $this->buildFields();
        }

        return $this->fields;
    }

    public function readData(): DataCollection
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->source;
        $collection = new DataCollection($this->getFields());
        $this->applyFilters([], [PaginationDataFilter::class]);
        $this->setItemsTotal($collection);
        $this->applyFilters([PaginationDataFilter::class]);

//        $sql = $queryBuilder->getQuery()->getSql();
//        $params = $queryBuilder->getQuery()->getParameters();
//        print_r($sql);
//        echo '<hr>';
//        print_r($params);
//        dd($queryBuilder->getQuery()->getScalarResult());

        foreach ($queryBuilder->getQuery()->getResult() as $item) {
            $collection->addItem(new DataItem($item));
        }

        return $collection;
    }

    protected function buildFields()
    {
        $buildFieldsEvent = new OnQueryBuilderFieldsBuildEvent($this->source);
        $this->eventDispatcher->dispatch($buildFieldsEvent);
        $this->fields = $buildFieldsEvent->getFields();
    }

    protected function setItemsTotal(DataCollection $collection)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = clone($this->getSource());
        $queryBuilder
            ->resetDQLPart('select')
            ->resetDQLPart('groupBy')
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