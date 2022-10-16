<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Helper;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\DataType\DataTypeInterface;
use A2Global\A2Platform\Bundle\DataBundle\DataType\ObjectDataType;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use ReflectionClass;
use Symfony\Contracts\Translation\TranslatorInterface;

class EntityHelper
{
    public static array $cachedEntityFields = [];

    public static array $cachedDataTypes = [];

    protected array $entityListCached = [];

    private const TYPICAL_FIELD_TRANSLATION_PREFIX = 'data.typical_entity.field.';

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface    $translator,
        protected                        $dataTypes,
    ) {
    }

    public function getEntityList()
    {
        if (!$this->entityListCached) {
            $this->entityListCached = $this->entityManager
                ->getConfiguration()
                ->getMetadataDriverImpl()
                ->getAllClassNames();
        }

        return $this->entityListCached;
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

    public function getName($objectOrClassName)
    {
        $name = StringUtility::getShortClassName($objectOrClassName);
        $code = 'data.entity.' . StringUtility::toSnakeCase($name) . '.title';
        $translated = $this->translator->trans($code);

        return $translated === $code ? StringUtility::normalize($name) : $translated;
    }

    public function getFieldName($objectOrClassName, $fieldName)
    {
        $entityNameSnaked = StringUtility::toSnakeCase(StringUtility::getShortClassName($objectOrClassName));
        $code = self::TYPICAL_FIELD_TRANSLATION_PREFIX . $fieldName;
        $translated = $this->translator->trans($code);

        if ($translated != $code) {
            return $translated;
        }
        $code = sprintf(
            'data.entity.%s.field.%s',
            $entityNameSnaked,
            StringUtility::toCamelCase($fieldName)
        );
        $translated = $this->translator->trans($code);

        return $translated === $code ? StringUtility::normalize($fieldName) : $translated;
    }

    public static function getEntityFields($class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

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
}