<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Form\Extension;

use A2Global\A2Platform\Bundle\PlatformBundle\Helper\TranslationHelper;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class FormLabelTranslationExtension extends AbstractTypeExtension
{
    public function __construct(
        protected TranslationHelper $translationHelper,
    ) {

    }

    public static function getExtendedTypes(): iterable
    {
        return [
            FormType::class,
        ];
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['label'] = $this->translationHelper
            ->translate($view->vars['label'] ?? $view->vars['name']);

        parent::buildView($view, $form, $options);
    }
}