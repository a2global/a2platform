<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('', name: 'default')]
    public function defaultAction(): Response
    {
        return new Response('Default admin page');
    }
}
