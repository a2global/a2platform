<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Entity;

use A2Global\A2Platform\Bundle\DataBundle\Repository\TagMappingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=TagMappingRepository::class)
 * @ORM\Table(name="data_tags_mapping")
 */
class TagMapping
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $targetClass;

    /**
     * @ORM\Column(type="integer")
     */
    private $targetId;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tag;

    /**
     * @codeCoverageIgnore
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTargetClass(): ?string
    {
        return $this->targetClass;
    }

    public function setTargetClass(string $targetClass): self
    {
        $this->targetClass = $targetClass;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTargetId(): ?int
    {
        return $this->targetId;
    }

    public function setTargetId(int $targetId): self
    {
        $this->targetId = $targetId;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }
}
