<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use Doctrine\ORM\EntityManagerInterface;

class EntityDataImporter
{
    public const STRATEGY_ADD = 1;

    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function import($entity, DataCollection $sourceData, $mapping, $strategy = self::STRATEGY_ADD)
    {
        /** @var DataItem $sourceObject */
        foreach ($sourceData->getItems() as $sourceObject) {
            $targetObject = $this->createNewObject($entity);

            foreach ($mapping as $sourceFieldNumber => $targetFieldName) {
                if (!$targetFieldName) {
                    continue;
                }
                ObjectHelper::setProperty($targetObject, $targetFieldName, $sourceObject->getValue($sourceFieldNumber));
            }
            $this->entityManager->persist($targetObject);
        }
        $this->entityManager->flush();
    }

    protected function createNewObject($entity)
    {
        return new $entity;
    }
}