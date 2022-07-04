<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Entity;

trait CommentableEntityTrait
{
    protected $comments;

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }
}