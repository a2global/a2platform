<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Request;

use A2Global\A2Platform\Bundle\CoreBundle\Handler\Response\ResponseHandlerInterface;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRequest
{
    const ACTION_INDEX = 'index';
    const ACTION_VIEW = 'view';
    const ACTION_EDIT = 'edit';
    const ACTION_DELETE = 'delete';

    public function __construct(
        protected Request $httpRequest,
        protected string $action,
        protected string $subjectName,
        protected string $subjectClass,
        protected bool $isAdmin,
        protected ResponseHandlerInterface $responseHandler,
        protected FormTypeInterface $form,
        protected string $routeNameIndex,
        protected string $routeNameView,
        protected string $routeNameEdit,
        protected string $routeNameDelete,
    ) {
    }

    public function getHttpRequest(): Request
    {
        return $this->httpRequest;
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

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getResponseHandler(): ResponseHandlerInterface
    {
        return $this->responseHandler;
    }

    public function getForm(): FormTypeInterface
    {
        return $this->form;
    }

    public function getRouteNameIndex(): string
    {
        return $this->routeNameIndex;
    }

    public function getRouteNameView(): string
    {
        return $this->routeNameView;
    }

    public function getRouteNameEdit(): string
    {
        return $this->routeNameEdit;
    }

    public function getRouteNameDelete(): string
    {
        return $this->routeNameDelete;
    }
}