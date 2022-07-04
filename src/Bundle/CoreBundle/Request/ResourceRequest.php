<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Request;

use A2Global\A2Platform\Bundle\CoreBundle\Response\Handler\ResponseHandlerInterface;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;

class ResourceRequest
{
    const ACTION_INDEX = 'index';
    const ACTION_VIEW = 'view';

    public function __construct(
        protected string $action,
        protected string $subjectName,
        protected string $subjectClass,
        protected string $subjectBundleName,
        protected bool $isAdmin,
        protected ResponseHandlerInterface $responseHandler,
    ) {
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getSubjectName(): string
    {
        return $this->subjectName;
    }

    public function getSubjectNamePlural(): string
    {
        return StringUtility::pluralize($this->subjectName);
    }

    public function getSubjectClass(): string
    {
        return $this->subjectClass;
    }

    public function getSubjectBundleName(): string
    {
        return $this->subjectBundleName;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getResponseHandler(): ResponseHandlerInterface
    {
        return $this->responseHandler;
    }
}