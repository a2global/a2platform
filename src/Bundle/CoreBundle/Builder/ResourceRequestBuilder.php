<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Response\Handler\AdminHtmlResponseHandler;
use A2Global\A2Platform\Bundle\CoreBundle\Response\Handler\FrontendHtmlResponseHandler;
use A2Global\A2Platform\Bundle\CoreBundle\Response\Handler\JsonResponseHandler;
use A2Global\A2Platform\Bundle\CoreBundle\Response\Handler\ResponseHandlerInterface;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class ResourceRequestBuilder
{
    public function __construct(
        protected FrontendHtmlResponseHandler $frontendHtmlResponseHandler,
        protected AdminHtmlResponseHandler $adminHtmlResponseHandler,
        protected JsonResponseHandler $jsonResponseHandler,
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function build(Request $request, string $action, bool $isAdmin = false): ResourceRequest
    {
        $requestControllerWithMethod = $request->attributes->get('_controller');
        $tmp = explode('::', $requestControllerWithMethod);
        $requestController = $tmp[0];
        $subjectClass = constant($requestController . '::' . 'RESOURCE_SUBJECT_CLASS');
        $subjectName = StringUtility::getShortClassName($subjectClass);
        $subjectClassParts = explode('\\', $requestController);
        $bundleName = mb_substr($subjectClassParts[3], 0, -6);

        if (!class_exists($subjectClass)) {
            throw new Exception('Failed to initialize subject configuration');
        }

        return new ResourceRequest(
            $action, $subjectName, $subjectClass, $bundleName, $isAdmin, $this->getResponseHandler($request, $isAdmin)
        );
    }

    protected function getResponseHandler(Request $request, bool $isAdmin = false): ResponseHandlerInterface
    {
        $acceptHeader = $request->headers->get('accept');

        if (stristr($acceptHeader, 'application/json')) {
            return $this->jsonResponseHandler;
        }

        if ($isAdmin) {
            return $this->adminHtmlResponseHandler;
        }

        return $this->frontendHtmlResponseHandler;
    }
}