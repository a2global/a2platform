<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventListener;

use A2Global\A2Platform\Bundle\DataBundle\Decorator\EntityCommentDecorator;
use A2Global\A2Platform\Bundle\DataBundle\Entity\CommentableEntityInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntityPostLoadListener
{
    public function __construct(
        protected EntityCommentDecorator $entityCommentDecorator,
//        protected EntityTagDecorator $entityTagDecorator,
    ) {
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof CommentableEntityInterface) {
            $this->entityCommentDecorator->decorate($args->getEntity());
        }

//        if ($args->getEntity() instanceof TaggableEntityInterface) {
//            $this->entityTagDecorator->decorate($args->getEntity());
//        }
    }
}