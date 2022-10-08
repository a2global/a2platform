<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Provider;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Registry\DataReaderRegistry;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormProvider
{
    private const ENTITY_FIELD_TYPES_SCALAR = [
        'integer',
        'string',
        'text',
        'boolean',
        'float',
        'decimal',
        'date',
        'datetime',
    ];

    private const FORM_FIELDS_MAPPING = [
        'many_to_one' => null,
    ];

    public function __construct(
        protected FormFactoryInterface $formFactory,
        protected DataReaderRegistry   $dataReaderRegistry,
        protected EntityHelper         $entityHelper,
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

    public function getImportMappingFormProvider($entity, $filepath, $filename, $filetype)
    {
        $targetFields = [
            'Ignore' => '',
        ];

        foreach (EntityHelper::getEntityFields($entity) as $entityFieldName => $entityFieldType) {
            if (!in_array($entityFieldType, self::ENTITY_FIELD_TYPES_SCALAR)) {
                continue;
            }
            $targetFields[StringUtility::normalize($entityFieldName)] = $entityFieldName;
        }
        $dataReader = $this->dataReaderRegistry->findDataReader($filepath);
        $fileFields = $dataReader->getFields();
        $form = $this->formFactory->create();
        $form->add('filename', HiddenType::class, ['data' => $filename]);
        $form->add('filetype', HiddenType::class, ['data' => $filetype]);
        $form->add('entity', HiddenType::class, ['data' => $entity]);
        $mappingForm = $form->add('mapping', null, ['compound' => true])->get('mapping');
        $i = 0;

        foreach ($fileFields as $field) {
            $mappingForm->add($i, ChoiceType::class, [
                'label' => StringUtility::normalize($field),
                'choices' => $targetFields,
                'required' => false,
            ]);
            $i++;
        }

        return $form;
    }
}