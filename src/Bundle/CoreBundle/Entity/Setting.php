<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Entity;

use A2Global\A2Platform\Bundle\CoreBundle\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 * @ORM\Table(name="settings")
 */
class Setting
{
    public const PARAMETER_PREFIX = 'a2platform';
    public const CACHE_FILEPATH = 'config_a2platform';
    public const TRANSLATION_PREFIX = 'a2platform.settings';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $value = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): self
    {
        $this->value = $value;

        return $this;
    }
}
