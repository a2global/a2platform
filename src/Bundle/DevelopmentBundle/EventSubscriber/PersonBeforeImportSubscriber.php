<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Event\Import\OnItemBeforeImportEvent;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonBeforeImportSubscriber implements EventSubscriberInterface
{
    // :D
    public function updateEmail(OnItemBeforeImportEvent $event)
    {
        if($event->getEntity() !== Person::class){
            return;
        }
        $data = $event->getData();
        $data['email'] = StringUtility::toSnakeCase($data['fullname']) . '@a2platform';
        $event->setData($data);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnItemBeforeImportEvent::class => 'updateEmail',
        ];
    }
}