<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Helper;

use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\DataTypeInterface;
use A2Global\A2Platform\Bundle\PlatformBundle\Data\Type\ObjectDataType;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Attribute;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Exception;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class EntityHelper
{
    protected array $entityMetadataCached = [];

    protected array $entityListCached = [];

    protected iterable $dataTypes;

    public const TYPICAL_TITLE_FIELDS = [
        'name',
        'fullname',
        'title',
    ];

    public function __construct(
        protected EntityManagerInterface                   $entityManager,
        #[TaggedIterator('a2platform.data.type')] iterable $dataTypes,
    ) {
        $this->dataTypes = $dataTypes;
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

    public static function getReadableTitle(object $object = null, bool $entityNameWithIdByDefault = true): ?string
    {
        if (is_null($object)) {
            return '';
        }

        if (method_exists($object, '__toString')) {
            return (string) $object;
        }

        foreach (self::TYPICAL_TITLE_FIELDS as $field) {
            $method = 'get' . $field;

            if (method_exists($object, $method)) {
                return (string) $object->$method();
            }
        }

        if (!$entityNameWithIdByDefault) {
            return null;
        }

        return sprintf(
            '%s #%s',
            StringUtility::toReadable(StringUtility::getShortClassName($object)),
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
            if ($relation['type'] === ClassMetadataInfo::MANY_TO_ONE) {
                $fields[$relation['fieldName']] = 'many_to_one';
            }
        }
        $sortedFields = [];

        foreach ((new ReflectionClass($className))->getProperties() as $property) {
            if (!array_key_exists($property->getName(), $fields)) {
                continue;
            }
            $sortedFields[$property->getName()] = $fields[$property->getName()];
        }

        return $sortedFields;
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

    public function getEntityMetadata(string $className): ClassMetadata
    {
        if (!array_key_exists($className, $this->entityMetadataCached)) {
            $this->entityMetadataCached[$className] = $this->entityManager->getClassMetadata($className);
        }

        return $this->entityMetadataCached[$className];
    }
}
