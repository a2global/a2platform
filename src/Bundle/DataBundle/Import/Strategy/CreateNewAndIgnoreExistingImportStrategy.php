<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import\Strategy;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use A2Global\A2Platform\Bundle\DataBundle\Event\Import\OnItemBeforeImportEvent;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateNewAndIgnoreExistingImportStrategy extends AbstractImportStrategy
{
    const NAME = 'Create new records and don`t update existing records';

    public function __construct(
        protected EntityManagerInterface   $entityManager,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function processItem(string $entity, array $data, string $identificationField): string
    {
        // Dispatch event with raw data, for modify purposes
        $event = new OnItemBeforeImportEvent($data);
        $this->eventDispatcher->dispatch($event);
        $data = $event->getData();

        if (!isset($data[$identificationField])) {
            throw new Exception('Identification field must be mapped');
        }

        if (empty($data[$identificationField])) {
            throw new Exception('Identification field is empty');
        }
        $objects = $this->entityManager->getRepository($entity)->findBy([
            $identificationField => $data[$identificationField],
        ]);

        if (count($objects) > 0) {
            return self::RESULT_SKIPPED;
        }
        $object = $this->createNewObject($entity);

        foreach ($data as $fieldName => $fieldValue) {
            ObjectHelper::setProperty($object, $fieldName, $fieldValue);
        }
        $this->entityManager->persist($object);
        $this->entityManager->flush();

        return self::RESULT_CREATED;
    }
}