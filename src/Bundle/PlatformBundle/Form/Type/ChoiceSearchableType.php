<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Form\Type;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoiceSearchableType extends AbstractType
{
    public function __construct(
        protected ParameterBagInterface $parameters,
    ) {
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($this->parameters->get('kernel.environment') === 'behat') {
            return;
        }
        $view->vars['attr']['data-search-picker'] = '1';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        if ($this->parameters->get('kernel.environment') === 'behat') {
            return;
        }
        $resolver->setDefaults([
            'widget' => 'single_text',
            'html5' => false,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}