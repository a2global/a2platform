<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Helper;

use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\DataTypeInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\ObjectDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Exception;

class EntityHelper
{
    protected array $entityMetadataCached = [];

    protected array $entityListCached = [];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected                        $dataTypes,
    ) {
    }

    public function getEntityList(): array
    {
        if (!$this->entityListCached) {
            $this->entityListCached = $this->entityManager
                ->getConfiguration()
                ->getMetadataDriverImpl()
                ->getAllClassNames();
        }

        return $this->entityListCached;
    }

    public function getEntityFields(string $className): array
    {
        $classMetadata = $this->getEntityMetadata($className);
        $fields = [];

        foreach ($classMetadata->getFieldNames() as $fieldName) {
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);
            $fields[$fieldName] = $fieldMapping['type'];
        }

        foreach ($classMetadata->getAssociationMappings() as $relation) {
            $fields[$relation['fieldName']] = 'relation';
        }

        return $fields;
    }

    public function resolveDataTypeByFieldType($fieldType): DataTypeInterface
    {
        /** @var DataTypeInterface $dataType */
        foreach ($this->dataTypes as $dataType) {
            if ($dataType::supportsByOrmType($fieldType)) {
                return $dataType;
            }
        }

        return new ObjectDataType();
    }

    public static function getProperty(mixed $object, string $propertyName)
    {
        foreach (['', 'get', 'is', 'has'] as $prefix) {
            $method = $prefix . StringUtility::toPascalCase($propertyName);

            if (method_exists($object, $method)) {
                return $object->{$method}();
            }
        }

        throw new Exception(
            sprintf(
                'Failed to get data %s from %s via get/is/has+%s',
                $propertyName,
                get_class($object),
                StringUtility::toPascalCase($object)
            )
        );
    }

    public static function setProperty(mixed $object, string $propertyName, mixed $propertyValue)
    {
        foreach (['', 'set'] as $prefix) {
            $method = $prefix . StringUtility::toPascalCase($propertyName);

            if (method_exists($object, $method)) {
                return $object->{$method}($propertyValue);
            }
        }

        throw new Exception(
            sprintf(
                'Failed to set data %s to %s via set+%s',
                $propertyName,
                get_class($object),
                StringUtility::toPascalCase($method)
            )
        );
    }

    public static function getReadableTitle(mixed $object, string $nullValue = '')
    {
        if (!$object) {
            return $nullValue;
        }

        if (method_exists($object, '__toString')) {
            return (string)$object;
        }

        foreach (static::$identityFields as $field) {
            $method = 'get' . $field;

            if (method_exists($object, $method)) {
                return (string)$object->$method();
            }
        }

        return sprintf(
            '%s #%s',
            StringUtility::normalize(StringUtility::getShortClassName($object)),
            $object->getId()
        );
    }

    public static function getFieldTypeFromAnnotation($property, AnnotationReader $annotationReader)
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

    protected function getEntityMetadata(string $className): ClassMetadata
    {
        if (!array_key_exists($className, $this->entityMetadataCached)) {
            $this->entityMetadataCached[$className] = $this->entityManager->getClassMetadata($className);
        }

        return $this->entityMetadataCached[$className];
    }
}