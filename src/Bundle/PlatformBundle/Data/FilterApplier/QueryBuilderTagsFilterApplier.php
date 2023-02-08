<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\FilterApplier;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\PlatformBundle\Entity\Tag;
use A2Global\A2Platform\Bundle\PlatformBundle\Entity\TagMapping;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\DataFilterInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter\TagsDataFilter;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Reader\QueryBuilderDataReader;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class QueryBuilderTagsFilterApplier implements FilterApplierInterface
{
    public function supports(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null): bool
    {
//        dd($dataReader);
        return $dataReader instanceof QueryBuilderDataReader && $filter instanceof TagsDataFilter;
    }

    public function apply(DataReaderInterface $dataReader, DataFilterInterface $filter, string $fieldName = null)
    {
        /** @var TagsDataFilter $filter */
        $tags = explode(',', $filter->getValue());

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $dataReader->getSource();
        $rootAlias = QueryBuilderUtility::getPrimaryAlias($queryBuilder);
        $rootClass = QueryBuilderUtility::getPrimaryClass($queryBuilder);
        $queryBuilder
            ->join(TagMapping::class, 'tagMapping', Join::WITH, 'tagMapping.targetId = '.$rootAlias.'.id AND tagMapping.targetClass=:tagMappingTargetClass')
            ->leftJoin('tagMapping.tag', 'tag')
            ->andWhere('tag.name in (:tags)')
            ->groupBy($rootAlias.'.id')
            ->having('COUNT(DISTINCT tag.name) = ' . count($tags))
            ->setParameter('tagMappingTargetClass', $rootClass)
            ->setParameter('tags', $tags)
;
//            ->setFirstResult(max($filter->getPage()-1, 0) * $filter->getLimit())
//            ->setMaxResults($filter->getLimit());
    }
}