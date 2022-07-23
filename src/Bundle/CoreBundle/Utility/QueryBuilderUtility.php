<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use A2Global\A2Platform\Bundle\DataBundle\DataType\BooleanType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateTimeType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DecimalType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\FloatType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\IntegerType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\ObjectType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\StringType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\TextType;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Exception;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class QueryBuilderUtility
{
    public const DATATYPE_MAPPING = [
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

    public static function getPrimaryClass(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getRootEntities()[0];
    }

    public static function getPrimaryAlias(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getRootAliases()[0];
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

    public static function getEntityFields($class, $fieldName = null): array
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        $properties = $reflectionClass->getProperties();
        $fields = [];

        foreach ($properties as $property) {
            $annotation = $annotationReader->getPropertyAnnotation($property, Column::class);

            if (!$annotation) {
                continue;
            }
            $field = [
                'name' => $annotation->name ?? $property->getName(),
                'type' => $annotation->type,
                'typeResolved' => self::DATATYPE_MAPPING[$annotation->type] ?? null,
            ];

            if ($fieldName == $field['name']) {
                return $field;
            }

            $fields[] = $field;
        }

        return $fields;
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

        return $annotation->targetEntity;
    }

    public static function getFieldPathByName(QueryBuilder $queryBuilder, $fieldName)
    {
        foreach ($queryBuilder->getDQLPart('select') as $select) {
            $fieldPath = $select->getParts()[0];
            $pathParts = explode('.', $fieldPath);

            if($fieldName === $pathParts[1]){
                return $fieldPath;
            }
        }
    }
}