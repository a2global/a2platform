<?php

namespace A2Global\A2Platform\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestApiController extends AbstractController
{
    public function listAction(Request $request)
    {
        return new JsonResponse(['action' => 'list']);
    }

    public function showAction(Request $request)
    {
        return new JsonResponse(['action' => 'show']);
    }
}