<?php

namespace A2Global\A2Platform\Bundle\AuthBundle\DependencyInjection;

use A2Global\A2Platform\Bundle\AuthBundle\AuthBundle;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(
            mb_strtolower(StringUtility::getShortClassName(AuthBundle::class, 'Bundle'))
        );

        // @formatter:off
        $treeBuilder->getRootNode()
            ->children()
//                ->arrayNode('native')
//                    ->children()
//                        ->booleanNode('enable')->defaultTrue()->end()
//                    ->end()
//                ->end()
                ->arrayNode('oauth')
                    ->children()
                        ->arrayNode('google')
                            ->children()
//                                ->booleanNode('enable')->defaultFalse()->end()
                                ->scalarNode('client_id')->end()
                                ->scalarNode('client_secret')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        // @formatter:on

        return $treeBuilder;
    }
}