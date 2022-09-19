<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Provider;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormProvider
{
    private const FORM_FIELDS_MAPPING = [
//        'integer' => NumberType::class,
        'many_to_one' => null,
    ];

    public function __construct(
        protected FormFactoryInterface $formFactory
    ) {
    }

    public function getFor(mixed $object): FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder(FormType::class, null, [
            'data_class' => get_class($object),
        ]);

        foreach (EntityHelper::getEntityFields($object) as $fieldName => $fieldType) {
            if ($fieldName === 'id') {
                continue;
            }

            if (!array_key_exists($fieldType, self::FORM_FIELDS_MAPPING)) {
                $formBuilder->add($fieldName);

                continue;
            }
            $formFieldType = self::FORM_FIELDS_MAPPING[$fieldType];

            if (is_null($formFieldType)) {
                continue;
            }
        }

        return $formBuilder->getForm();
    }
}