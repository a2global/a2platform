<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Entity;

interface CommentableEntityInterface
{
    public function getId();

    public function getComments();

    public function setComments($comments);
}