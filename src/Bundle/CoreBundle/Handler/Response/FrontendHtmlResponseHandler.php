<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Handler\Response;

use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class FrontendHtmlResponseHandler implements ResponseHandlerInterface
{
    public function __construct(
        protected Environment $environment
    ) {
    }

    public function supports(Request $request, $isAdmin = false): bool
    {
        return (stristr($request->headers->get('accept'), 'text/html') !== false) && !$isAdmin;
    }

    public function createResponse(ResourceRequest $request, array $data): Response
    {
        $template = $this->getTemplate(
            $request->getSubjectBundleName(),
            $request->getSubjectName(),
            $request->getAction()
        );

        return new Response($this->environment->render($template, $data));
    }

    protected function getTemplate($bundleName, $subjectName, $action): string
    {
        $customTemplate = sprintf(
            '@%s/frontend/%s/%s.html.twig',
            $bundleName,
            StringUtility::toSnakeCase($subjectName),
            $action
        );

        if ($this->environment->getLoader()->exists($customTemplate)) {
            return $customTemplate;
        }

        return sprintf('@Core/frontend/entity/%s.html.twig', $action);
    }
}