<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

class EntityDataImporter
{
    public const STRATEGY_ADD = 1;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ManagerRegistry $managerRegistry,
    ) {
    }

    public function import($entity, DataCollection $sourceData, $mapping, $strategy = self::STRATEGY_ADD): array
    {
        $result = [
            'imported' => 0,
            'errors' => [],
        ];

        $line = 1;
        /** @var DataItem $sourceObject */
        foreach ($sourceData->getItems() as $sourceObject) {
            try {
                $targetObject = $this->createNewObject($entity);

                foreach ($mapping as $sourceFieldNumber => $targetFieldName) {
                    if (!$targetFieldName) {
                        continue;
                    }
                    ObjectHelper::setProperty($targetObject, $targetFieldName, $sourceObject->getValue($sourceFieldNumber));
                }
                $this->entityManager->persist($targetObject);
                $this->entityManager->flush();
                $result['imported']++;
            } catch (Throwable $exception) {
                $result['errors'][$line] = $exception->getMessage();

                if(!$this->entityManager->isOpen()){
                    $this->managerRegistry->reset();
                }
            }
            $line++;
        }

        return $result;
    }

    protected function createNewObject($entity)
    {
        return new $entity;
    }
}