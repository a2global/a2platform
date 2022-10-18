<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Manager;

use A2Global\A2Platform\Bundle\DataBundle\Entity\Tag;
use A2Global\A2Platform\Bundle\DataBundle\Entity\TaggableEntityInterface;
use A2Global\A2Platform\Bundle\DataBundle\Entity\TagMapping;
use Doctrine\ORM\EntityManagerInterface;

class TagManager
{
    public const SEPARATOR = ',';

    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function getTagsAsString(TaggableEntityInterface $object, $appendToSeparator = ''): string
    {
        $tagsAsSimleArray = array_map(function (Tag $tag) {
            return $tag->getName();
        }, $object->getTags());

        return implode(self::SEPARATOR . $appendToSeparator, $tagsAsSimleArray);
    }

    public function getTagsFromString($string)
    {
        $tags = explode(self::SEPARATOR, $string);
        $tags = array_map('trim', $tags);
        $tags = array_filter($tags, function ($item) {
            return !empty($item);
        });
        $tags = array_unique($tags);
        $result = [];

        foreach ($tags as $tag) {
            if (!$tag) {
                continue;
            }
            $result[] = $this->getOrCreateTag($tag);
        }

        return $result;
    }

    public function updateTagsFor(TaggableEntityInterface $object)
    {
        $tagsMapping = $this->entityManager->getRepository(TagMapping::class)->findBy([
            'targetClass' => get_class($object),
            'targetId' => $object->getId(),
        ]);
        $tags = $object->getTags();

        foreach ($tagsMapping as $tagMapping) {
            if (!$this->tagExists($tagMapping->getTag(), $tags)) {
                // if old tag doesn't not exists in new tags - delete old tag
                $this->entityManager->remove($tagMapping);
            } else {
                // if old tag exists in new tags - delete it from new, because new tags will become 'tagsToAdd'
                $tags = $this->removeTag($tagMapping->getTag(), $tags);
            }
        }

        foreach ($tags as $tag) {
            $this->mapTag($object, $tag);
        }
    }

    protected function getOrCreateTag($name): Tag
    {
        $tag = $this->entityManager->getRepository(Tag::class)->findOneBy(['name' => $name]);

        if ($tag) {
            return $tag;
        }
        $tag = (new Tag())->setName($name);
        $this->entityManager->persist($tag);

        return $tag;
    }

    protected function tagExists(Tag $tag, array $tags)
    {
        /** @var Tag $item */
        foreach ($tags as $item) {
            if ($item->getId() == $tag->getId()) {
                return true;
            }
        }

        return false;
    }

    protected function removeTag(Tag $tag, array $tags)
    {
        /** @var Tag $item */
        foreach ($tags as $key => $item) {
            if ($item->getId() == $tag->getId()) {
                unset($tags[$key]);

                return $tags;
            }
        }

        return $tags;
    }

    protected function mapTag(TaggableEntityInterface $object, Tag $tag)
    {
        $objectClass = get_class($object);
        $existing = $this->entityManager->getRepository(TagMapping::class)->findOneBy([
            'targetClass' => $objectClass,
            'targetId' => $object->getId(),
            'tag' => $tag,
        ]);

        if ($existing) {
            return;
        }
        $tagMapping = (new TagMapping())
            ->setTargetClass($objectClass)
            ->setTargetId($object->getId())
            ->setTag($tag);
        $this->entityManager->persist($tagMapping);
    }
}