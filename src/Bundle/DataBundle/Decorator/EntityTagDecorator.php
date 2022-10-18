<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Decorator;

use A2Global\A2Platform\Bundle\DataBundle\Entity\CommentableEntityInterface;
use A2Global\A2Platform\Bundle\DataBundle\Entity\TaggableEntityInterface;
use A2Global\A2Platform\Bundle\DataBundle\Entity\TagMapping;
use Doctrine\ORM\EntityManagerInterface;

class EntityTagDecorator
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function decorate(CommentableEntityInterface $object)
    {
        /** @var TaggableEntityInterface $object */
        $mappings = $this->entityManager
            ->getRepository(TagMapping::class)
            ->findBy([
                'targetClass' => get_class($object),
                'targetId' => $object->getId(),
            ], ['createdAt' => 'DESC']);
        $tags = [];

        /** @var TagMapping $mapping */
        foreach ($mappings as $mapping) {
            $tags[] = $mapping->getTag();
        }
        $object->setTags($tags);
    }
}