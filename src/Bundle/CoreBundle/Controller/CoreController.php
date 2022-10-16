<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoreController extends AbstractController
{
    #[Route('app-default-page-resolver', name: 'app_default_page_resolver')]
    public function resolveAndRedirectToAppDefaultPageAction(): Response
    {
        return $this->redirect('/');
    }
}
