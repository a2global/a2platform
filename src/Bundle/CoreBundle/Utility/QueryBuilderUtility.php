<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class QueryBuilderUtility
{
    public static function getPrimaryClass(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getDQLPart('from')[0]->getFrom();
    }

    public static function getPrimaryAlias(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getDQLPart('from')[0]->getAlias();
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

    public static function getEntityFields($class): array
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        $properties = $reflectionClass->getProperties();
        $fields = [];

        foreach($properties as $property){
            $annotation = $annotationReader->getPropertyAnnotation($property, Column::class);

            if(!$annotation){
                continue;
            }
            $fields[] = [
                'name' => $annotation->name ?? $property->getName(),
                'type' => $annotation->type,
            ];
        }

        return $fields;
    }
}