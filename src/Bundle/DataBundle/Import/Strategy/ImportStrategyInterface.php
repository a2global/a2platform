<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Import\Strategy;

interface ImportStrategyInterface
{
    public const RESULT_CREATED = 'created';
    public const RESULT_UPDATED = 'updated';
    public const RESULT_SKIPPED = 'skipped';

    public function getName(): string;

    public function processItem(string $entity, array $data, string $identificationField): string;
}