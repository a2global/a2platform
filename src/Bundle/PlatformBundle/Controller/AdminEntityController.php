<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/entity/', name: 'admin_entity_')]
class AdminEntityController
{
    #[Route('list', name: 'list')]
    public function listAction(Request $request)
    {
    }
}