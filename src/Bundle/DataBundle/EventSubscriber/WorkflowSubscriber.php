<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Workflow\Event\EnterEvent;

class WorkflowSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.leave' => 'setUpContext',
        ];
    }

    public function setUpContext($event)
    {
        $transitionData = $this->requestStack->getMainRequest()->request->get('data', []);
        
        if(!$transitionData){
            return;
        }
        $event->getMarking()->setContext($transitionData);
    }
}