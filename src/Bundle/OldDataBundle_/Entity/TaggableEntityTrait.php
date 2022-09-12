<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Entity;

trait TaggableEntityTrait
{
    protected $tags;

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
}