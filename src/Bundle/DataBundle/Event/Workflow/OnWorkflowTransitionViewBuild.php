<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Event\Workflow;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\HttpFoundation\ParameterBag;

class OnWorkflowTransitionViewBuild
{
    protected ParameterBag $parameters;
    protected string $content = '';

    public function __construct(
        protected $object,
        protected ?string $workflowName,
        protected string  $transitionName,
    ) {
        $this->parameters = new ParameterBag();
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getWorkflowName(): ?string
    {
        return $this->workflowName;
    }

    public function getTransitionName(): string
    {
        return $this->transitionName;
    }

    public function getParameters(): ParameterBag
    {
        return $this->parameters;
    }

    public function addParameter($name, $value): self
    {
        $this->parameters->set($name, $value);

        return $this;
    }

    public function addContent($content)
    {
        $this->content .= PHP_EOL . $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getName()
    {
        return sprintf(
            'workflow.%s.transition_view.%s',
            StringUtility::toSnakeCase($this->getWorkflowName() ?: StringUtility::getShortClassName($this->getObject())),
            $this->getTransitionName(),
        );
    }
}