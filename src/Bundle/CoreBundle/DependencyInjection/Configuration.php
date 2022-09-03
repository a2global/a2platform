<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\DependencyInjection;

use A2Global\A2Platform\Bundle\CoreBundle\CoreBundle;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(
            mb_strtolower(StringUtility::getShortClassName(CoreBundle::class, 'Bundle'))
        );

        // @formatter:off
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('something')->defaultValue('treebuilder_value')->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }
}