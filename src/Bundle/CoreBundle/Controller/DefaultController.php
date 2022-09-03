<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function defaultAction()
    {
        // if no website enabled
        if (!false) {
            return $this->redirectToRoute('authentication_default');
        }
    }
}