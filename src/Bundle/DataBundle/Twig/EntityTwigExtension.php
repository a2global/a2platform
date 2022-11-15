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

    public function getFunctions()
    {
        return [
            new TwigFunction('get_entity_configuration', [$this, 'getEntityConfiguration']),
        ];
    }

    public function getEntityConfiguration($object)
    {
        return $this->entityConfigurationBuilder->build($object);
    }
}