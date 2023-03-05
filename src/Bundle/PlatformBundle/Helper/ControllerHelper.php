<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Helper;

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

    public function redirectBackOrTo($url = null, $addToReferer = [])
    {
        $referer = $this->requestStack->getMainRequest()->headers->get('referer');

        if ($referer) {
            $targetUrl = $referer;

            if ($addToReferer) {
                $urlParts = parse_url($referer);
                parse_str($urlParts['query'] ?? '', $parameters);
                $parameters = array_merge($parameters, $addToReferer);
                $targetUrl = sprintf(
                    '%s://%s%s%s?%s',
                    $urlParts['scheme'],
                    $urlParts['host'],
                    ($urlParts['port'] ?? 80) != 80 ? ':' . $urlParts['port'] : '',
                    $urlParts['path'],
                    http_build_query($parameters)
                );
            }

            return new RedirectResponse($targetUrl);
        }

        if ($url) {
            return new RedirectResponse($url);
        }

        return new RedirectResponse($this->router->generate('app_default'));
    }
}