<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Provider;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Entity\Comment;
use A2Global\A2Platform\Bundle\DataBundle\Event\Workflow\OnWorkflowTransitionFormBuild;
use A2Global\A2Platform\Bundle\DataBundle\Form\CommentFormType;
use A2Global\A2Platform\Bundle\DataBundle\Import\Strategy\ImportStrategyInterface;
use A2Global\A2Platform\Bundle\DataBundle\Registry\DataReaderRegistry;
use A2Global\A2Platform\Bundle\DataBundle\Registry\ImportStrategyRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\RouterInterface;

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
        'date' => DateType::class,
        'datetime' => DateTimeType::class,
    ];

    public function __construct(
        protected FormFactoryInterface     $formFactory,
        protected DataReaderRegistry       $dataReaderRegistry,
        protected EntityHelper             $entityHelper,
        protected ImportStrategyRegistry   $importStrategyRegistry,
        protected RouterInterface          $router,
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function getFor(mixed $object): FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder(FormType::class, $object, [
            'data_class' => get_class($object),
        ]);

        foreach (EntityHelper::getEntityFields($object) as $fieldName => $fieldType) {
            if ($fieldName === 'id') {
                continue;
            }

            if (array_key_exists($fieldType, self::FORM_FIELDS_MAPPING) && is_null(self::FORM_FIELDS_MAPPING[$fieldType])) {
                continue;
            }
            $formBuilder->add($fieldName, self::FORM_FIELDS_MAPPING[$fieldType] ?? null, [
                'label' => $this->entityHelper->getFieldName($object, $fieldName),
            ]);
        }

        return $formBuilder->getForm();
    }

    public function getImportMappingFormProvider($entity, $filepath, $filename, $filetype): FormInterface
    {
        $targetObjectFields = [];

        foreach (EntityHelper::getEntityFields($entity) as $entityFieldName => $entityFieldType) {
            if (!in_array($entityFieldType, self::ENTITY_FIELD_TYPES_SCALAR)) {
                continue;
            }
            $targetObjectFields[StringUtility::normalize($entityFieldName)] = $entityFieldName;
        }
        $dataReader = $this->dataReaderRegistry->findDataReader($filepath);
        $fileFields = $dataReader->getFields();
        $form = $this->formFactory->create();
        $form->add('filename', HiddenType::class, ['data' => $filename]);
        $form->add('filetype', HiddenType::class, ['data' => $filetype]);
        $form->add('entity', HiddenType::class, ['data' => $entity]);
        $form->add('identifier_field', ChoiceType::class, ['choices' => $targetObjectFields]);

        // Import strategies
        $strategies = ['Please select:' => null];

        /** @var ImportStrategyInterface $importStrategy */
        foreach ($this->importStrategyRegistry->get() as $importStrategy) {
            $strategies[$importStrategy->getName()] = get_class($importStrategy);
        }
        $form->add('strategy', ChoiceType::class, [
            'choices' => $strategies,
            'choice_attr' => [
                'Please select:' => ['disabled' => 'disabled'],
            ],
            'required' => true,
        ]);

        // Data mapping
        $mappingForm = $form->add('mapping', null, ['compound' => true])->get('mapping');
        $i = 0;
        $availableTargetFields = array_merge(['Ignore' => ''], $targetObjectFields);
        unset($availableTargetFields['Id'], $availableTargetFields['Created at'], $availableTargetFields['Updated at'],);

        foreach ($fileFields as $field) {
            $mappingForm->add($i, ChoiceType::class, [
                'label' => StringUtility::normalize($field),
                'choices' => $availableTargetFields,
                'required' => false,
                'choice_attr' => $this->getChoicesAttr($field, $availableTargetFields),
            ]);
            $i++;
        }

        return $form;
    }

    public function getCommentForm($object = null): FormInterface
    {
        $comment = new Comment();

        if ($object) {
            $comment
                ->setTargetClass(get_class($object))
                ->setTargetId($object->getId());
        }

        return $this->formFactory->create(CommentFormType::class, $comment, [
            'action' => $this->router->generate('admin_data_comment_add'),
        ]);
    }

    public function getTransitionForm($object, $workflowName, $transitionName): FormInterface
    {
        $form = $this->formFactory->create(FormType::class, null, [
            'action' => $this->router->generate('admin_data_workflow_apply_transition'),
        ]);
        $form->add('objectClass', HiddenType::class, ['data' => get_class($object)]);
        $form->add('objectId', HiddenType::class, ['data' => $object->getId()]);
        $form->add('workflowName', HiddenType::class, ['data' => $workflowName]);
        $form->add('transitionName', HiddenType::class, ['data' => $transitionName]);

        // Dispatching event in order to customize transition form
        $event = new OnWorkflowTransitionFormBuild($object, $workflowName, $transitionName, $form);
        $this->eventDispatcher->dispatch($event, $event->getName());

        $form->add('submit', SubmitType::class, [
            'label' => 'Apply',
            'attr' => [
                'data-crud-workflow-transition-apply' => $workflowName . ':' . $transitionName,
            ],
        ]);

        return $form;
    }

    protected function getChoicesAttr($fileField, $entityFields): array
    {
        if (in_array($fileField, $entityFields) || in_array(StringUtility::toCamelCase($fileField), $entityFields)) {
            return [
                StringUtility::normalize($fileField) => ['selected' => 'selected'],
            ];
        }

        return [];
    }
}