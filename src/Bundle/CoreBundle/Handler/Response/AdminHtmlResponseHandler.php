<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Handler\Response;

use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class AdminHtmlResponseHandler implements ResponseHandlerInterface
{
    public function __construct(
        protected Environment $environment,
        protected FormFactoryInterface $formFactory,
        protected EntityManagerInterface $entityManager,
        protected RouterInterface $router,
    ) {
    }

    public function supports(Request $request, $isAdmin = false): bool
    {
        return (stristr($request->headers->get('accept'), 'text/html') !== false) && $isAdmin;
    }

    public function createResponse(ResourceRequest $request, array $data): Response
    {
        // todo: registry with supports
        if ($request->getAction() === ResourceRequest::ACTION_INDEX) {
            return $this->getIndexResponse($data);
        }

        if ($request->getAction() === ResourceRequest::ACTION_VIEW) {
            return $this->getViewResponse($data);
        }

        if ($request->getAction() === ResourceRequest::ACTION_EDIT) {
            return $this->getEditResponse($request, $data);
        }
    }

    protected function getIndexResponse(array $data): Response
    {
        return new Response($this->environment->render('@Admin/resource/index.html.twig', $data));
    }

    protected function getViewResponse(array $data): Response
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

    protected function getEditResponse(ResourceRequest $resourceRequest, array $data): Response
    {
        $objectViewUrl = $this->router->generate($resourceRequest->getRouteNameView(), [
            'id' => $data['object']->getId(),
        ]);
        $form = $this->formFactory->create(get_class($resourceRequest->getForm()), $data['object']);
        $form->handleRequest($resourceRequest->getHttpRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return new RedirectResponse($objectViewUrl);
        }

        return new Response($this->environment->render('@Admin/resource/edit.html.twig', [
            'form' => $form->createView(),
            'objectViewUrl' => $objectViewUrl,
        ]));
    }
}