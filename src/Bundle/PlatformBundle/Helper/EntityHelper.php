<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Helper;

use Doctrine\ORM\EntityManagerInterface;

class EntityHelper
{
    protected array $entityListCached = [];

    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function getEntityList(): array
    {
        if (!$this->entityListCached) {
            $this->entityListCached = $this->entityManager
                ->getConfiguration()
                ->getMetadataDriverImpl()
                ->getAllClassNames();
        }

        return $this->entityListCached;
    }
}