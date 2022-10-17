<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Decorator;

use A2Global\A2Platform\Bundle\DataBundle\Entity\Comment;
use A2Global\A2Platform\Bundle\DataBundle\Entity\CommentableEntityInterface;
use Doctrine\ORM\EntityManagerInterface;

class EntityCommentDecorator
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function decorate(CommentableEntityInterface $object)
    {
        /** @var CommentableEntityInterface $object */
        $object->setComments(
            $this->entityManager
                ->getRepository(Comment::class)
                ->findBy([
                    'targetClass' => get_class($object),
                    'targetId' => $object->getId(),
                ], ['createdAt' => 'DESC'])
        );
    }
}