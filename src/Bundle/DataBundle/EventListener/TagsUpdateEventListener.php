<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventListener;

use A2Global\A2Platform\Bundle\DataBundle\Entity\TaggableEntityInterface;
use A2Global\A2Platform\Bundle\DataBundle\Manager\TagManager;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TagsUpdateEventListener
{
    protected $objectsToUpdate = [];

    public function __construct(
        protected TagManager $tagManager,
    ) {
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        if (!$args->getObject() instanceof TaggableEntityInterface) {
            return;
        }

        if (!$args->getObject()->isTagsNeedsToBeUpdated()) {
            return;
        }
        $this->objectsToUpdate[] = $args->getObject();
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (!count($this->objectsToUpdate)) {
            return;
        }

        foreach ($this->objectsToUpdate as $key => $object) {
            $this->tagManager->updateTagsFor($object);
        }
        $this->objectsToUpdate = [];
        $args->getEntityManager()->flush();
    }
}