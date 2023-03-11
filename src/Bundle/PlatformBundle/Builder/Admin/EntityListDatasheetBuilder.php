<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu\EntityMenuBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu\MenuBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Datasheet\Datasheet;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Phalcon\Db\Column;
use Symfony\Contracts\Translation\TranslatorInterface;

class EntityListDatasheetBuilder
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface    $translator,
        protected EntityHelper           $entityHelper,
        protected EntityMenuBuilder      $entityMenuBuilder,
//        protected RouterInterface            $router,
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
        foreach ($this->entityHelper->getEntityFields($entityClassName) as $fieldName => $fieldType) {
            $datasheet->getColumn($fieldName)->setTitle($fieldName);
        }

        // Add controls
//        $datasheet->addControl(
//            'Import',
//            $this->router->generate('admin_data_import_upload', ['entity' => $entityClassName])
//        );

        // Update typical columns
        if ($includeColumns) {
            $entityMenu = $this->entityMenuBuilder->getSingleEntityMenu($entityClassName);
            $defaultMenuItem = MenuBuilder::getDefault($entityMenu);

            if ($defaultMenuItem) {
                $datasheet->getColumn($this->resolvePrimaryActionColumnName($entityClassName))
                    ->setLink([$defaultMenuItem->getRouteName(), $defaultMenuItem->getRouteParameters()])
                    ->setBold(true);
            }
        }

        foreach ($datasheet->getColumns() as $column) {
            $column->setTitle(
                sprintf('%s.field.%s', StringUtility::toSnakeCase($entityClassName), $column->getName())
            );
        }

        // Mass actions
//        $datasheet->setMassActions($this->entityConfigurationBuilder->build(new $entityClassName())->getMassActions());

        return $datasheet;
    }

    protected function resolvePrimaryActionColumnName($entityClassName): string
    {
        foreach ($this->entityHelper->getEntityFields($entityClassName) as $fieldName => $fieldType) {
            if (in_array($fieldName, Datasheet::TYPICAL_PRIMARY_ACTION_FIELDS)) {
                return $fieldName;
            }
        }

        return 'id';
    }
}