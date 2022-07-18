<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Response\Handler;

use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AdminHtmlResponseHandler implements ResponseHandlerInterface
{
    public function __construct(
        protected Environment $environment
    ) {
    }

    public function createResponse(ResourceRequest $request, array $data): Response
    {
        // todo: registry with supports
        if ($request->getAction() === ResourceRequest::ACTION_VIEW) {
            return $this->getViewResponse($data);
        } else {
            return $this->getIndexResponse($data);
        }
    }

    protected function getIndexResponse(array $data): Response
    {
        return new Response($this->environment->render('@Admin/resource/index.html.twig', $data));
    }

    public function getViewResponse(array $data): Response
    {
        $fields = QueryBuilderUtility::getEntityFields(get_class($data['object']));
        $data['objectData'] = [];

        foreach ($fields as $field) {
            $data['objectData'][$field['name']] = [
                'name' => StringUtility::normalize($field['name']),
                'value' => '<i class="text-muted">(unable to render)</i>',
            ];
            $getter = 'get' . $field['name'];

            if (!method_exists($data['object'], $getter)) {
                continue;
            }

            try {
                $value = $data['object']->$getter();
            } catch (Exception $exception) {
                continue;
            }

            if (!is_scalar($value)) {
                continue;
            }
            $data['objectData'][$field['name']]['value'] = $value;
        }

        return new Response($this->environment->render('@Admin/resource/view.html.twig', $data));
    }
}