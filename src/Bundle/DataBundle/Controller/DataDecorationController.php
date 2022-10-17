<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\ControllerHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\DataBundle\Entity\Comment;
use A2Global\A2Platform\Bundle\DataBundle\Import\EntityDataImporter;
use A2Global\A2Platform\Bundle\DataBundle\Provider\DatasheetProvider;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use A2Global\A2Platform\Bundle\DataBundle\Registry\DataReaderRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("admin/data/", name="admin_data_")
 */
class DataDecorationController extends AbstractController
{
    /**
     * @Route("comment/add", name="comment_add")
     */
    public function commentAddAction(Request $request)
    {
        $form = $this->get(FormProvider::class)->getCommentForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $this->get(TranslatorInterface::class)->trans('data.comment.added'));

            return $this->get(ControllerHelper::class)->redirectBackOrTo(
                $this->generateUrl('admin_data_view', [
                    'entity' => $comment->getTargetClass(),
                    'id' => $comment->getTargetId(),
                ])
            );
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            FormProvider::class,
            TranslatorInterface::class,
            ControllerHelper::class,
        ]);
    }
}