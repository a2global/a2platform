<?php

namespace A2Global\A2Platform\Bundle\SampleBundle\Entity;

use A2Global\A2Platform\Bundle\SampleBundle\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: 'sample_author')]
class Author
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('Default')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('Default')]
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
