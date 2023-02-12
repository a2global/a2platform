<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Helper;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use ReflectionClass;

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

    public function getEntityFields($className): array
    {
        $classMetadata = $this->getEntityMetadata($className);
        $fields = [];

        foreach($classMetadata->getFieldNames() as $fieldName){
            $fieldMapping = $classMetadata->getFieldMapping($fieldName);
            $fields[$fieldName] = $fieldMapping['type'];
        }

        return $fields;

//        $fields = array_map(function($field) use ($classMetadata){
//
//        }, $classMetadata->getFieldMappings());

        $fields = array_merge($class->getColumnNames(), $fields);
        foreach ($fields as $index => $field) {
            if ($class->isInheritedField($field)) {
                unset($fields[$index]);
            }
        }
//        foreach ($class->getAssociationMappings() as $name => $relation) {
//            if (!$class->isInheritedAssociation($name)) {
//                foreach ($relation['joinColumns'] as $joinColumn) {
//                    $fields[] = $joinColumn['name'];
//                }
//            }
//        }
        return $fields;
    }

    protected function getEntityMetadata($className): ClassMetadata
    {
        if (!array_key_exists($className, $this->entityMetadataCached)) {
            $this->entityMetadataCached[$className] = $this->entityManager->getClassMetadata($className);
        }

        return $this->entityMetadataCached[$className];
    }

    public static function getEntityFieldsOld($class): array
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        if (array_key_exists($class, self::$entityFieldsCached)) {
            return self::$entityFieldsCached[$class];
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

        return self::$entityFieldsCached[$class] = $fields;
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