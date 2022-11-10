<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;

class EntityDataBuilder
{
    public function __construct(
        protected EntityHelper $entityHelper,
    ) {
    }

    public function getData($object): array
    {
        $data = [];

        foreach (EntityHelper::getEntityFields($object) as $fieldName => $fieldType) {
            $dataType = $this->entityHelper->resolveDataTypeByFieldType($fieldType);

            if ($fieldName === 'id') {
                continue;
            }
            $data[] = [
                'name' => $this->entityHelper->getFieldName($object, $fieldName),
                'value' => $dataType::getReadablePreview(ObjectHelper::getProperty($object, $fieldName)),
            ];
        }

        return $data;
    }
}