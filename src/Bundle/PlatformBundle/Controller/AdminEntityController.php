<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use A2Global\A2Platform\Bundle\PlatformBundle\Event\Admin\BuildEntityListEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Utility\StringUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/admin/entity/', name: 'admin_entity_')]
class AdminEntityController extends AbstractController
{
    #[Route('list', name: 'list')]
    public function listAction(Request $request)
    {
        $className = $request->get('className');
        $event = new BuildEntityListEvent($className);
        $this->container
            ->get(EventDispatcherInterface::class)
            ->dispatch($event, BuildEntityListEvent::NAME);

        return $this->render('@Platform/admin/entity/list.html.twig', [
            'className' => $className,
            'entityName' => StringUtility::toReadable(StringUtility::getShortClassName($className)),
            'datasheet' => $event->getDatasheet(),
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            EventDispatcherInterface::class,
        ]);
    }
}