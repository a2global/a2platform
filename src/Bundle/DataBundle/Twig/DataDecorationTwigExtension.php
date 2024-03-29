<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Entity\TaggableEntityInterface;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DataDecorationTwigExtension extends AbstractExtension
{
    public function __construct(
        protected FormProvider $formProvider,
        protected Environment  $twig,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('tag_list', [$this, 'viewTagList'], ['is_safe' => ['html']]),
            new TwigFunction('comment_list', [$this, 'viewCommentList'], ['is_safe' => ['html']]),
            new TwigFunction('comment_form', [$this, 'viewCommentForm'], ['is_safe' => ['html']]),
        ];
    }

    public function viewTagList($object)
    {
        if (!$object instanceof TaggableEntityInterface) {
            return '';
        }

        return $this->twig->render('@Data/data/tag_list.html.twig', [
            'object' => $object,
        ]);
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