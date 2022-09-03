<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Form;

use A2Global\A2Platform\Bundle\CoreBundle\Entity\Setting;
use A2Global\A2Platform\Bundle\CoreBundle\Form\Type\ContainerType;
use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\BooleanNode;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SettingsForm extends AbstractType
{
    protected PropertyAccessor $propertyAccessor;

    public function __construct(
        protected ParameterBagInterface $parameters,
        protected                       $configurations,
    ) {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ConfigurationInterface $configuration */
        foreach ($this->configurations as $configuration) {
            $this->handleNode($configuration->getConfigTreeBuilder()->buildTree(), $builder);
        }
    }

    protected function handleNode($node, $builder, $path = [])
    {
        $path[] = $node->getName();

        if (!$node instanceof ArrayNode) {
            $label = Setting::TRANSLATION_PREFIX . '.' . implode('.', $path);
            $value = null;//$this->propertyAccessor->getValue(
//                $this->parameters->get(Setting::PARAMETER_PREFIX),
//                '[' . implode('][', $path) . ']'
//            );
        }
        $builder->add($node->getName(), $this->resolveFormType($node), [
            'label' => $label ?? null,
            'required' => false,
        ]);
        $builder = $builder->get($node->getName());
        $builder->setData($value ?? null);

        if ($node instanceof ArrayNode) {
            foreach ($node->getChildren() as $child) {
                $this->handleNode($child, $builder, $path);
            }
        }
    }

    protected function resolveFormType($node)
    {
        if ($node instanceof ArrayNode) {
            return ContainerType::class;
        }

        if ($node instanceof BooleanNode) {
            return CheckboxType::class;
        }

        return TextType::class;
    }
}