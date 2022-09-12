<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Controller\Behat;

use A2Global\A2Platform\Bundle\DevelopmentBundle\Helper\BehatHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("development/behat/", name="development_behat_")
 */
class BehatController extends AbstractController
{
    /**
     * @Route("coverage", name="coverage")
     * @codeCoverageIgnore
     */
    public function behatCoverageAction()
    {
        $this->get(BehatHelper::class)->generateCoverageReport();

        return new RedirectResponse('/etc/behat-coverage-report/index.html');
    }

    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            BehatHelper::class,
        ]);
    }
}