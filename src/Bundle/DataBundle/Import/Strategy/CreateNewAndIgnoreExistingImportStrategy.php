<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import\Strategy;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CreateNewAndIgnoreExistingImportStrategy extends AbstractImportStrategy
{
    const NAME = 'Create new records and don`t update existing records';

    public function __construct(
        protected EntityManagerInterface   $entityManager,
    ) {
    }

    public function processItem(string $entity, array $data, string $identificationField): string
    {
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