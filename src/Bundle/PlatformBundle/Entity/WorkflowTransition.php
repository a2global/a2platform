<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Entity;

use A2Global\A2Platform\Bundle\PlatformBundle\Repository\WorkflowTransitionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: WorkflowTransitionRepository::class)]
class WorkflowTransition
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $targetClass;

    #[ORM\Column]
    private ?int $targetId;

    #[ORM\Column(length: 255)]
    private ?string $workflowName;

    #[ORM\Column(length: 255)]
    private ?string $transitionName;

    #[ORM\Column(nullable: true)]
    private array $context;

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

    public function getWorkflowName()
    {
        return $this->workflowName;
    }

    public function setWorkflowName($workflowName): self
    {
        $this->workflowName = $workflowName;
        return $this;
    }

    public function getTransitionName()
    {
        return $this->transitionName;
    }

    public function setTransitionName($transitionName): self
    {
        $this->transitionName = $transitionName;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context): self
    {
        $this->context = $context;
        return $this;
    }
}
