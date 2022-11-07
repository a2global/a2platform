<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Provider;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineExtensions\Query\Postgresql\StringAgg;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DatasheetProvider
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RouterInterface        $router,
        protected TranslatorInterface    $translator,
        protected EntityHelper           $entityHelper,
    ) {
    }

    public function getDefaultEntityListDatasheet($entityClassName, $includeColumns = true): Datasheet
    {
        // Create datasheet with the title
        $datasheet = new Datasheet(
            $this->entityManager->getRepository($entityClassName)->createQueryBuilder('a'),
            $this->translator->trans('data.crud.list.datasheet.title', [
                '%entity%' => $this->entityHelper->getName($entityClassName),
            ]),
            'data_index_' . StringUtility::toSnakeCase($entityClassName),
        );

        // Set column titles translated
        foreach (EntityHelper::getEntityFields($entityClassName) as $fieldName => $fieldType) {
            $datasheet->getColumn($fieldName)
                ->setTitle($this->entityHelper->getFieldName($entityClassName, $fieldName));
        }

        // Add controls
        $datasheet->addControl(
            'Import',
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