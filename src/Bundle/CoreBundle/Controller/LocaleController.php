<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\ControllerHelper;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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

        return $this->get(ControllerHelper::class)->redirectBackOrTo();
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ControllerHelper::class,
        ]);
    }
}
