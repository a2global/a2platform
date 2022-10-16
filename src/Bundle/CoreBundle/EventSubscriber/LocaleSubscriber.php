<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * https://symfony.com/doc/5.4/session/locale_sticky_session.html#creating-a-localesubscriber
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    const DEFAULT_LOCALE = 'en';

    public function setLocale(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }
        $request->setLocale($request->getSession()->get('_locale', self::DEFAULT_LOCALE));
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => ['setLocale', 20],
        ];
    }
}