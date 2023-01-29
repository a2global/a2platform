<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('', name: 'default')]
    public function defaultAction()
    {
        return $this->render('@Platform/admin/dashboard.html.twig');
    }
}
