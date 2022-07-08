<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Controller\Admin;

use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin_development_", name="admin_development_")
 */
class DebugController extends AbstractController
{
    /**
     * @Route("datasheet", name="datasheet")
     */
    public function datasheetAction()
    {
        $listeners = $this->get(EventDispatcherInterface::class)->getListeners();
        dd($listeners);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            EventDispatcherInterface::class,
        ]);
    }
}