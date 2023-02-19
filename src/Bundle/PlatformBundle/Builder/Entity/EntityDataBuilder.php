<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Entity;

use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;

class EntityDataBuilder
{
    public function __construct(
        protected EntityHelper $entityHelper,
    ) {
    }

    public function getData($object): array
    {
        $data = [];

        foreach ($this->entityHelper->getEntityFields(get_class($object)) as $fieldName => $fieldType) {
            $dataType = $this->entityHelper->resolveDataTypeByFieldType($fieldType);

            if ($fieldName === 'id') {
                continue;
            }
            $data[] = [
                'name' => $fieldName,
                'value' => $dataType::getReadablePreview(EntityHelper::getProperty($object, $fieldName)),
            ];
        }

        return $data;
    }
}