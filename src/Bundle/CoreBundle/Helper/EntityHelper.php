<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Helper;

use Doctrine\ORM\EntityManagerInterface;

class EntityHelper
{
    protected $entityListCached;

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function getEntityList()
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