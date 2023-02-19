<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Entity\EntityDataBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Admin\BuildEntityListEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('view', name: 'view')]
    public function viewAction(Request $request)
    {
        $object = $this->container
            ->get(EntityManagerInterface::class)
            ->getRepository($request->get('className'))
            ->find($request->get('id'));

        return $this->render('@Platform/admin/entity/view.html.twig', [
            'object' => $object,
            'data' => $this->container->get(EntityDataBuilder::class)->getData($object),
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            EventDispatcherInterface::class,
            EntityManagerInterface::class,
            EntityDataBuilder::class,
        ]);
    }
}