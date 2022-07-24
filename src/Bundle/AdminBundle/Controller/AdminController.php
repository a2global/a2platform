<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Controller;

use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="_default")
     */
    public function homepageAction()
    {
        return $this->render('@Admin/homepage.html.twig', [
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            DatasheetBuilder::class,
        ]);
    }
}