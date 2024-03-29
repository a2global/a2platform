<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Form\Type;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TextTagsType extends AbstractType
{
    public function __construct(
        protected ParameterBagInterface $parameters,
        protected TranslatorInterface   $translator,
    ) {
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-form-control-tags'] = '1';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget' => 'single_text',
            'html5' => false,
            'mapped' => false,
            'label' => $this->translator->trans('data.tags.form_field_title'),
            'required' => false,
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}