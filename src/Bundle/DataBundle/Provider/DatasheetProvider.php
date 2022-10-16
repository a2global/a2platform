<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Provider;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DatasheetProvider
{
    private const TYPICAL_FIELDS = [
        'id',
        'createdAt',
        'updatedAt',
        'user',
        'name',
        'title',
        'password',
        'enabled',
        'isActive',
    ];

    private const TYPICAL_FIELD_TRANSLATION_PREFIX = 'data.typical_entity.field.';

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RouterInterface        $router,
        protected TranslatorInterface    $translator,
    ) {
    }

    public function getDefaultEntityListDatasheet($entityClassName, $includeColumns = true): Datasheet
    {
        $snakedEntityName = StringUtility::toSnakeCase(StringUtility::getShortClassName($entityClassName));
        $objectName = $this->translator->trans('data.entity.'.$snakedEntityName.'.title');

        // Create datasheet with the title
        $datasheet = new Datasheet(
            $this->entityManager->getRepository($entityClassName)->createQueryBuilder('a'),
            $this->translator->trans('data.crud.list.datasheet.title', [
                '%entity%' => $objectName,
            ]),
        );

        // Set column titles
        foreach (EntityHelper::getEntityFields($entityClassName) as $fieldName => $fieldType) {
            if (in_array($fieldName, self::TYPICAL_FIELDS)) {
                $title = self::TYPICAL_FIELD_TRANSLATION_PREFIX . $fieldName;
            } else {
                $title = sprintf(
                    'data.entity.%s.field.%s',
                    StringUtility::toCamelCase(StringUtility::getShortClassName($entityClassName)),
                    StringUtility::toCamelCase($fieldName)
                );
            }
            $translatedTitle = $this->translator->trans($title);
            $finalTitle = $translatedTitle === $title ? StringUtility::normalize($fieldName) : $translatedTitle;
            $datasheet->getColumn($fieldName)->setTitle($finalTitle);
        }

        // Add controls
        $datasheet->addControl(
            'ui.import',
            $this->router->generate('admin_data_import_upload', ['entity' => $entityClassName])
        );

        // Update typical columns
        if ($includeColumns) {
            $datasheet->getColumn($this->resolveIdentityColumnName($entityClassName))
                ->setLink(['admin_data_view', ['entity' => $entityClassName]])
                ->setBold(true);
        }

        return $datasheet;
    }

    protected function resolveIdentityColumnName($entityClassName): string
    {
        foreach (EntityHelper::getEntityFields($entityClassName) as $fieldName => $fieldType) {
            if (in_array($fieldName, ObjectHelper::$identityFields)) {
                return $fieldName;
            }
        }

        return 'id';
    }
}