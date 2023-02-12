<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use A2Global\A2Platform\Bundle\PlatformBundle\Event\Admin\BuildEntityListEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/admin/entity/', name: 'admin_entity_')]
class AdminEntityController extends AbstractController
{
    #[Route('list/{className}', name: 'index')]
    public function indexAction($className)
    {
        $event = new BuildEntityListEvent($className);
        $this->container
            ->get(EventDispatcherInterface::class)
            ->dispatch($event, BuildEntityListEvent::NAME);

        return $this->render('@Platform/admin/entity/list.html.twig', [
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