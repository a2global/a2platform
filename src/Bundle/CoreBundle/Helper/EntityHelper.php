<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Helper;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use ReflectionClass;

class EntityHelper
{
    public static array $cachedEntityFields = [];

    protected array $entityListCached = [];

    public function __construct(
        protected EntityManagerInterface $entityManager
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