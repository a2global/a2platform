<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Provider;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class DatasheetProvider
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RouterInterface        $router,
    ) {
    }

    public function getDefaultEntityListDatasheet($entityClassName, $includeColumns = true): Datasheet
    {
        $datasheet = new Datasheet(
            $this->entityManager->getRepository($entityClassName)->createQueryBuilder('a'),
            'List of the ' . StringUtility::normalize(StringUtility::getShortClassName($entityClassName)),
        );
        $datasheet->addControl('Import', $this->router->generate('admin_data_import_upload', ['entity' => $entityClassName]));

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