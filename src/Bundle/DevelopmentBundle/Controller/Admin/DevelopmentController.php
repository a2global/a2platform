<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Controller\Admin;

use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Helper\BehatHelper;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/development/", name="admin_development_")
 */
class DevelopmentController extends AbstractController
{
    /**
     * @Route("datasheet", name="datasheet")
     */
    public function datasheetAction()
    {
        $listeners = $this->get(EventDispatcherInterface::class)->getListeners();
        dd($listeners);
    }

    /**
     * @Route("behat-coverage", name="behat_coverage")
     * @codeCoverageIgnore
     */
    public function behatCoverageAction()
    {
        $this->get(BehatHelper::class)->generateCoverageReport();

        return new RedirectResponse('/etc/behat-coverage-report/index.html');
    }

    /**
     * @Route("datasheet/invalid-data", name="datasheet_invalid_data")
     */
    public function invalidDatasheetDataAction()
    {
        $datasheet = new Datasheet(null);

        return $this->render('@Admin/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            EventDispatcherInterface::class,
            BehatHelper::class,
        ]);
    }
}