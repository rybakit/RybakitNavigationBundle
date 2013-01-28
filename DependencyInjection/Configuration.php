<?php

namespace Rybakit\Bundle\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('rybakit_navigation')
            ->children()
                ->scalarNode('template')
                    ->defaultValue('RybakitNavigationBundle::navigation.html.twig')
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
