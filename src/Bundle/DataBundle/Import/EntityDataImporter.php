<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import;

use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataItem;
use A2Global\A2Platform\Bundle\DataBundle\Registry\ImportStrategyRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

class EntityDataImporter
{
    protected array $result = [
        'errors' => [],
    ];
    protected int $line = 1;

    public function __construct(
        protected ManagerRegistry        $managerRegistry,
        protected ImportStrategyRegistry $importStrategyRegistry,
    ) {
    }

    public function import(string $entity, DataCollection $sourceData, array $mapping, string $strategy, string $identificationField): array
    {
        $strategy = $this->importStrategyRegistry->find($strategy);

        /** @var DataItem $sourceObject */
        foreach ($sourceData->getItems() as $sourceObject) {
            try {
                $targetObjectData = [];

                foreach ($mapping as $sourceFieldNumber => $targetFieldName) {
                    if (!$targetFieldName) {
                        continue;
                    }
                    $targetObjectData[$targetFieldName] = $sourceObject->getValue($sourceFieldNumber);
                }
                $result = $strategy->processItem($entity, $targetObjectData, $identificationField);
                $this->result[$result] = ($this->result[$result] ?? 0) + 1;
            } catch (Throwable $exception) {
                $this->result['errors'][$this->line] = $exception->getMessage();

                if (!$this->managerRegistry->getManager()->isOpen()) {
                    $this->managerRegistry->reset();
                }
            }
            $this->line++;
        }
        $this->result['total items'] = $this->line;

        return $this->result;
    }
}