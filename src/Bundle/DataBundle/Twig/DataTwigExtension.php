<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DataTwigExtension extends AbstractExtension
{
    public function __construct(
        protected FormProvider $formProvider,
        protected Environment  $twig,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('comment_list', [$this, 'viewCommentList'], ['is_safe' => ['html']]),
            new TwigFunction('comment_form', [$this, 'viewCommentForm'], ['is_safe' => ['html']]),
        ];
    }

    public function viewCommentList($object)
    {
        return $this->twig->render('@Data/data/comment_list.html.twig', [
            'object' => $object,
        ]);
    }

    public function viewCommentForm($object)
    {
        return $this->twig->render('@Data/data/comment_form.html.twig', [
            'form' => $this->formProvider->getCommentForm($object)->createView(),
        ]);
    }
}