<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Helper;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class ControllerHelper
{
    public function __construct(
        protected RequestStack    $requestStack,
        protected RouterInterface $router,
    ) {
    }

    public function redirectBackOrTo($url = null)
    {
        $referer = $this->requestStack->getMainRequest()->get('referer');

        if ($referer) {
            return new RedirectResponse($referer);
        }

        if ($url) {
            return new RedirectResponse($url);
        }

        return new RedirectResponse($this->router->generate('app_default_page_resolver'));
    }
}