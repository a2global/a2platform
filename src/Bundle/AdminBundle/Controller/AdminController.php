<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Controller;

use A2Global\A2Platform\Bundle\AdminBundle\Form\SettingsForm;
use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="default")
     */
    public function homepageAction()
    {
        return $this->render('@Admin/homepage.html.twig', [
        ]);
    }

    /**
     * @Route("/settings", name="settings")
     */
    public function settingsAction()
    {
        $form = $this->createForm(SettingsForm::class);

        return $this->render('@Admin/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            DatasheetBuilder::class,
        ]);
    }
}