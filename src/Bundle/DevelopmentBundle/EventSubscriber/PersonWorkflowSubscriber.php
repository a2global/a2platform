<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Workflow\Event\TransitionEvent;

class PersonWorkflowSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.person.transition.interview_with_hr' => 'setEnglishLevel',
            'workflow.person.transition.provide_offer' => 'setSalary',
        ];
    }

    public function setEnglishLevel(TransitionEvent $event)
    {
        $event->getSubject()->setVersion($event->getContext()['englishLevel']);
    }

    public function setSalary(TransitionEvent $event)
    {
        $event->getSubject()->setAge($event->getContext()['salary']);
    }
}