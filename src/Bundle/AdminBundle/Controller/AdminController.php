<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Controller;

use A2Global\A2Platform\Bundle\AdminBundle\Form\SettingsForm;
use A2Global\A2Platform\Bundle\CoreBundle\Manager\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function settingsAction(Request $request)
    {
        $form = $this->createForm(SettingsForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get(SettingsManager::class)->handleForm($form);
        }

        return $this->render('@Admin/form_vertical.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            SettingsManager::class,
        ]);
    }
}