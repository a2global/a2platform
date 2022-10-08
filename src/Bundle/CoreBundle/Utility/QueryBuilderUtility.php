<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use A2Global\A2Platform\Bundle\DataBundle\Exception\DatasheetBuildException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Exception;
use ReflectionClass;

class QueryBuilderUtility
{
    public static function getPrimaryClass(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getRootEntities()[0];
    }

    public static function getPrimaryAlias(QueryBuilder $queryBuilder)
    {
        $rootAliases = $queryBuilder->getRootAliases();

        return reset($rootAliases);
    }

    public static function getJoinByAlias(QueryBuilder $queryBuilder, $alias): Join
    {
        foreach ($queryBuilder->getDQLPart('join')[QueryBuilderUtility::getPrimaryAlias($queryBuilder)] as $join) {
            if ($join->getAlias() == $alias) {
                return $join;
            }
        }

        throw new Exception('Failed to find join by alias: ' . $alias);
    }

    // todo: cache!!!
    public static function getClassNameByAlias(QueryBuilder $queryBuilder, $alias)
    {
        if ($alias === self::getPrimaryAlias($queryBuilder)) {
            return self::getPrimaryClass($queryBuilder);
        }

        $joinPart = self::getJoinByAlias($queryBuilder, $alias);
        $joinPath = explode('.', $joinPart->getJoin());

        if ($joinPath[0] == self::getPrimaryAlias($queryBuilder)) {
            $aliasParentClassName = self::getPrimaryClass($queryBuilder);
        } else {
            $aliasParentClassName = self::getClassNameByAlias($queryBuilder, $joinPath[0]);
        }
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($aliasParentClassName);
        $property = $reflectionClass->getProperty($joinPath[1]);
        $annotation = $annotationReader->getPropertyAnnotation($property, ManyToOne::class);
        $targetEntity = $annotation->targetEntity;

        if (strpos($targetEntity, '\\') === false) {
            $targetEntity = StringUtility::getNameSpace($aliasParentClassName) . '\\' . $targetEntity;
        }

        return $targetEntity;
    }

    public static function getFieldPathByName(QueryBuilder $queryBuilder, $fieldName)
    {
        foreach ($queryBuilder->getDQLPart('select') as $select) {
            $firstSelect = $select->getParts()[0];

            if (str_contains($firstSelect, '.')) {
                throw new DatasheetBuildException('No support for complex dql');
            } else {
                return $firstSelect . '.' . $fieldName;
            }
        }
    }
}