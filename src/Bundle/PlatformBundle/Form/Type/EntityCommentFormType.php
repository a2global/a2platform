<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Form\Type;

use A2Global\A2Platform\Bundle\PlatformBundle\Entity\EntityComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityCommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('body', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Comment',
                    'rows' => 7,
                ],
            ])
            ->add('className', HiddenType::class)
            ->add('entityId', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntityComment::class,
        ]);
    }
}
