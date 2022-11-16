<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DataBundle\Event\EntityConfigurationBuildEvent;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
use A2Global\A2Platform\Bundle\DevelopmentBundle\PersonCvProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PersonConfigurationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            EntityConfigurationBuildEvent::class => 'addTabs',
        ];
    }

    public function addTabs(EntityConfigurationBuildEvent $event)
    {
        if ($event->getConfiguration()->getClassname() != Person::class) {
            return;
        }

        $event->getConfiguration()->addSidebarTab('cv', function (object $object) {
            return $object->getFullname() . ' is a very good person';
        });

        $event->getConfiguration()->addSidebarTab('recommendations', function (object $object) {
            return 'Only the best for ' . $object->getFullname();
        });
    }
}