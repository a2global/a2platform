<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Entity;

interface TaggableEntityInterface
{
    public function getId();

    public function getTags();

    public function setTags($tags);
}