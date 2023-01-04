<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Entity;

trait TaggableEntityTrait
{
    protected $tags = [];

    protected $tagsNeedsToBeUpdated = false;

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tagsNeedsToBeUpdated = true;
        $this->tags = $tags;

        return $this;
    }

    public function isTagsNeedsToBeUpdated()
    {
        return $this->tagsNeedsToBeUpdated;
    }
}