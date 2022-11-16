<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Builder\EntityConfigurationBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EntityTwigExtension extends AbstractExtension

{
    public function __construct(
        protected EntityConfigurationBuilder $entityConfigurationBuilder,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_entity_configuration', [$this, 'getEntityConfiguration']),
            new TwigFunction('get_entity_sidebar_content', [$this, 'getEntitySidebarTabContent'], ['is_safe' => ['html']]),
        ];
    }

    public function getEntityConfiguration($object)
    {
        return $this->entityConfigurationBuilder->build($object);
    }

    public function getEntitySidebarTabContent($object, $source): string
    {
        return $source($object);
    }
}