<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Entity;

use A2Global\A2Platform\Bundle\DevelopmentBundle\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @ORM\Table(name="development_persons")
 */
class Person
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
    private $fullname;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class)
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class)
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class)
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable="true")
     */
    private $age;

    /**
     * @ORM\Column(type="float", nullable="true")
     */
    private $version;

    /**
     * @ORM\Column(type="text")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phonenumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastActiveAt;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=8, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles = [];

    public function __toString(): string
    {
        return sprintf('#%s %s %s', $this->getId(), $this->getFirstname());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getVersion(): ?float
    {
        return $this->version;
    }

    public function setVersion(?float $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getLastActiveAt(): ?\DateTimeInterface
    {
        return $this->lastActiveAt;
    }

    public function setLastActiveAt(\DateTimeInterface $lastActiveAt): self
    {
        $this->lastActiveAt = $lastActiveAt;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
    
    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }
}
