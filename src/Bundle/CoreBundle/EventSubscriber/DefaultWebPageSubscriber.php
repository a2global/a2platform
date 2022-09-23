<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class DefaultWebPageSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected RequestStack    $requestStack,
        protected RouterInterface $router,
    ) {
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if (!$event->getThrowable() instanceof NotFoundHttpException) {
            return;
        }

        if ($this->requestStack->getMainRequest()->getMethod() !== Request::METHOD_GET) {
            return;
        }

        if (trim($this->requestStack->getMainRequest()->getPathInfo(), '/') !== '') {
            return;
        }

        $response = new RedirectResponse($this->router->generate('authentication_default'));
        $response->send();

        exit;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
