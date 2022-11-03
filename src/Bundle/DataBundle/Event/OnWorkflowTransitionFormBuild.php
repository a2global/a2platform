<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\Form\FormInterface;

class OnWorkflowTransitionFormBuild
{
    public function __construct(
        protected string        $objectClass,
        protected ?string       $workflowName,
        protected string        $transitionName,
        protected FormInterface $form,
    ) {
    }

    public function getObjectClass(): string
    {
        return $this->objectClass;
    }

    public function getWorkflowName(): ?string
    {
        return $this->workflowName;
    }

    public function getTransitionName(): string
    {
        return $this->transitionName;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getName()
    {
        return sprintf(
            'workflow.%s.transition_form.%s',
            StringUtility::toSnakeCase($this->getWorkflowName() ?: StringUtility::getShortClassName($this->getObjectClass())),
            $this->getTransitionName(),
        );
    }
}