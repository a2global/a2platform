<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use A2Global\A2Platform\Bundle\DataBundle\Repository\WorkflowTransitionRepository;

/**
 * @ORM\Entity(repositoryClass=WorkflowTransitionRepository::class)
 * @ORM\Table(name="data_workflow_transitions")
 */
class WorkflowTransition
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
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $workflowName;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $transitionName;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $context;

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
