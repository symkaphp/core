<?php


namespace Symka\Core\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('symka_core');
        $rootNode->children()
            ->arrayNode('admin_vertical_menu')
            ->arrayPrototype()
            ->children()
            ->scalarNode('title')->end()
            ->scalarNode('route')->end()
            //->scalarNode('password')->end()
            ->end()
            ->end()
            ->end()
            ->end();
        return $treeBuilder;
    }
}