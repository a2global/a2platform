<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

use A2Global\A2Platform\Bundle\DataBundle\DataType\BooleanType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateTimeType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DateType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DecimalType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\EntityType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\FloatType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\IntegerType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\ObjectType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\StringType;
use A2Global\A2Platform\Bundle\DataBundle\DataType\TextType;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Exception;
use ReflectionClass;

class QueryBuilderUtility
{
    static array $cachedEntityFields = [];

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

    // todo make cache for this
    public static function getEntityFieldsOld($class, $fieldName = null): array
    {
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        $properties = $reflectionClass->getProperties();
        $fields = [];

        foreach ($properties as $property) {
            $fieldType = self::getFieldTypeFromAnnotation($property, $annotationReader);

            if ($fieldType === false) {
                continue;
            }

            $field = [
                'name' => $property->getName(),
                'type' => $fieldType,
                'typeResolved' => self::DATATYPE_MAPPING[$fieldType] ?? null,
            ];

            if ($fieldName == $field['name']) {
                return $field;
            }

            $fields[] = $field;
        }

        return $fields;
    }

    public static function getEntityFields($class): array
    {
        if (array_key_exists($class, self::$cachedEntityFields)) {
            return self::$cachedEntityFields[$class];
        }
        $annotationReader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($class);
        $properties = $reflectionClass->getProperties();
        $fields = [];

        foreach ($properties as $property) {
            $fieldType = self::getFieldTypeFromAnnotation($property, $annotationReader);

            if ($fieldType === false) {
                continue;
            }
            $fields[$property->getName()] = $fieldType;
        }

        return self::$cachedEntityFields[$class] = $fields;
    }

    protected static function getFieldTypeFromAnnotation($property, AnnotationReader $annotationReader)
    {
        $annotations = $annotationReader->getPropertyAnnotations($property);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Column) {
                return $annotation->type ?? null;
            }

            if (in_array(get_class($annotation), [
                ManyToOne::class,
                OneToOne::class,
                ManyToMany::class,
            ])) {
                return StringUtility::toSnakeCase(StringUtility::getShortClassName($annotation));
            }
        }

        return false;
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
            $fieldPath = $select->getParts()[0];
            $pathParts = explode('.', $fieldPath);

            if ($fieldName === $pathParts[1]) {
                return $fieldPath;
            }
        }
    }
}