<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Handler\Response\ResponseHandlerInterface;
use A2Global\A2Platform\Bundle\CoreBundle\Registry\ResponseHandlerRegistry;
use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use ReflectionClass;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ResourceRequestBuilder
{
    public function __construct(
        protected ResponseHandlerRegistry $responseHandlerRegistry,
        protected EntityManagerInterface $entityManager,
        protected $formTypes,
        protected RouterInterface $router,
    ) {
    }

    public function build(Request $request, string $action, bool $isAdmin = false): ResourceRequest
    {
        $requestControllerWithMethod = $request->attributes->get('_controller');
        $tmp = explode('::', $requestControllerWithMethod);
        $requestController = $tmp[0];
        $objectClass = constant($requestController . '::' . 'RESOURCE_SUBJECT_CLASS');
        $objectName = StringUtility::getShortClassName($objectClass);

        if (!class_exists($objectClass)) {
            throw new Exception('Failed to initialize subject configuration');
        }

        $annotationReader = new AnnotationReader();
        $resourceControllerReflectionClass = new ReflectionClass($requestController);
        /** @var Route $annotation */
        $annotation = $annotationReader
            ->getClassAnnotation($resourceControllerReflectionClass, Route::class);
        $routeNamePrefix = $annotation->getName();

        return new ResourceRequest(
            $request,
            $action,
            $objectName,
            $objectClass,
            $isAdmin,
            $this->findResponseHandler($request, $isAdmin),
            $this->findObjectForm($objectClass),
            $routeNamePrefix . ResourceRequest::ACTION_INDEX,
            $routeNamePrefix . ResourceRequest::ACTION_VIEW,
            $routeNamePrefix . ResourceRequest::ACTION_EDIT,
            $routeNamePrefix . ResourceRequest::ACTION_DELETE,
        );
    }

    // todo: optimize
    protected function findObjectForm($objectClass): FormTypeInterface
    {
        /** @var FormTypeInterface $formType */
        foreach ($this->formTypes as $formType) {
            $optionsResolver = new OptionsResolver();
            $formType->configureOptions($optionsResolver);

            if (!$optionsResolver->hasDefault('data_class')) {
                continue;
            }
            $options = $optionsResolver->resolve();

            if ($options['data_class'] !== $objectClass) {
                continue;
            }

            return $formType;
        }
    }

    protected function findResponseHandler(Request $request, bool $isAdmin = false): ResponseHandlerInterface
    {
        /** @var ResponseHandlerInterface $responseHandler */
        foreach ($this->responseHandlerRegistry->get() as $responseHandler) {
            if ($responseHandler->supports($request, $isAdmin)) {
                return $responseHandler;
            }
        }

        throw new Exception('No suitable response handler found for the request');
    }
}