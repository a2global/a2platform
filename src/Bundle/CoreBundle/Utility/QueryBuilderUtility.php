<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use A2Global\A2Platform\Bundle\DataBundle\DataType\BooleanType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateTimeType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\FloatType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\IntegerType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\ObjectType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\StringType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\TextType;
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
        $dataTypeMapping = [
            'boolean' => BooleanType::class,
            'integer' => IntegerType::class,
            'float' => FloatType::class,
            'decimal' => DecimalType::class,
            'date' => DateType::class,
            'datetime' => DateTimeType::class,
            'string' => StringType::class,
            'text' => TextType::class,
            'array' => ObjectType::class,
            'json' => ObjectType::class,
        ];
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        $properties = $reflectionClass->getProperties();
        $fields = [];

        foreach ($properties as $property) {
            $annotation = $annotationReader->getPropertyAnnotation($property, Column::class);

            if (!$annotation) {
                continue;
            }
            $fields[] = [
                'name' => $annotation->name ?? $property->getName(),
                'type' => $annotation->type,
                'typeResolved' => $dataTypeMapping[$annotation->type] ?? null,
            ];
        }

        return $fields;
    }
}