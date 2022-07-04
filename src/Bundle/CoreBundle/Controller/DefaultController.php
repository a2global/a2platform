<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/a2', name: 'core_default')]
    public function defaultAction()
    {
        return $this->render('@Core/frontend/base.html.twig');
    }
}