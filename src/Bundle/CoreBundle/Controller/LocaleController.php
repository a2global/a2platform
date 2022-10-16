<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/locale/', name: 'locale_')]
class LocaleController extends AbstractController
{
    #[Route('set', name: 'set')]
    public function setAction(Request $request)
    {
        $locale = $request->get('locale', $this->getParameter('kernel.default_locale'));
        $request->getSession()->set('_locale', $locale);
        $this->getUser()->setLocale($locale);
        $this->getDoctrine()->getManager()->flush();

        return $request->headers->get('referer') ?
            $this->redirect($request->headers->get('referer')) :
            $this->redirectToRoute('app_default_page_resolver');
    }
}
