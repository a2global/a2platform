<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\Datasheet;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EntityListDatasheetBuilder
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
//        protected RouterInterface            $router,
        protected TranslatorInterface    $translator,
        protected EntityHelper           $entityHelper,
//        protected EntityConfigurationBuilder $entityConfigurationBuilder,
    )
    {
    }

    public function buildDefaultEntityListDatasheet($entityClassName, $includeColumns = true): Datasheet
    {
        $datasheet = new Datasheet(
            $this->entityManager->getRepository($entityClassName)->createQueryBuilder('a'),
            StringUtility::toReadable(StringUtility::getShortClassName($entityClassName)),
            'admin_entity_list__' . StringUtility::toSnakeCase($entityClassName),
        );

        // Set column titles translated
        foreach (EntityHelper::getEntityFields($entityClassName) as $fieldName => $fieldType) {
            $datasheet->getColumn($fieldName)->setTitle($fieldName);
        }

        // Add controls
//        $datasheet->addControl(
//            'Import',
//            $this->router->generate('admin_data_import_upload', ['entity' => $entityClassName])
//        );

        // Update typical columns
//        if ($includeColumns) {
//            $datasheet->getColumn($this->resolveIdentityColumnName($entityClassName))
//                ->setLink(['admin_data_click', ['entity' => $entityClassName]])
//                ->setBold(true);
//        }

        // Mass actions
//        $datasheet->setMassActions($this->entityConfigurationBuilder->build(new $entityClassName())->getMassActions());

        return $datasheet;
    }

//    protected function resolveIdentityColumnName($entityClassName): string
//    {
//        foreach (EntityHelper::getEntityFields($entityClassName) as $fieldName => $fieldType) {
//            if (in_array($fieldName, ObjectHelper::$identityFields)) {
//                return $fieldName;
//            }
//        }
//
//        return 'id';
//    }
}