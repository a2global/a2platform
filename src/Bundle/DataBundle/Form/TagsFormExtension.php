<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Form;

use A2Global\A2Platform\Bundle\DataBundle\Entity\TaggableEntityInterface;
use A2Global\A2Platform\Bundle\DataBundle\Manager\TagManager;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagsFormExtension extends AbstractTypeExtension
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected TagManager          $tagManager,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!is_subclass_of($builder->getDataClass(), TaggableEntityInterface::class)) {
            return;
        }
        $builder->add('tags', TextType::class, [
            'mapped' => false,
            'label' => $this->translator->trans('data.tags.form_field_title'),
            'required' => false,
            'attr' => [
                'data-form-control-tags' => '1',
            ]
        ]);
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $event->getForm()->get('tags')->setData($this->tagManager->getTagsAsString($event->getData(), ' '));
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $event->getData()->setTags($this->tagManager->getTagsFromString($event->getForm()->get('tags')->getData()));
        });
    }

    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}