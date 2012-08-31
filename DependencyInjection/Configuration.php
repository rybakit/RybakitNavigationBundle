<?php

namespace Rybakit\Bundle\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('rybakit_navigation')
            ->children()
                ->arrayNode('twig')
                ->addDefaultsIfNotSet()
                ->canBeUnset()
                ->children()
                    ->scalarNode('template')
                        ->defaultValue('bootstrap.html.twig')
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
