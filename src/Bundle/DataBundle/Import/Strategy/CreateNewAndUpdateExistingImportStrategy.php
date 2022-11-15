<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import\Strategy;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ObjectHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * @codeCoverageIgnore
 */
class CreateNewAndUpdateExistingImportStrategy extends AbstractImportStrategy
{
    const NAME = 'Create new records and update existing';

    public function __construct(
        protected EntityManagerInterface $entityManager,
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

        if (count($objects) > 1) {
            throw new Exception(sprintf(
                'Multiple (%s) objects found by `%s` = `%s`',
                count($objects),
                $identificationField,
                $data[$identificationField],
            ));
        }

        if (count($objects) === 1) {
            $object = reset($objects);
            $result = self::RESULT_UPDATED;
        } else {
            $object = $this->createNewObject($entity);
            $result = self::RESULT_CREATED;
        }

        foreach ($data as $fieldName => $fieldValue) {
            ObjectHelper::setProperty($object, $fieldName, $fieldValue);
        }

        if (!$object->getId()) {
            $this->entityManager->persist($object);
        }
        $this->entityManager->flush();

        return $result;
    }
}