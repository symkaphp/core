<?php


namespace Symka\Core\DependencyInjection;


use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('symka_core');
        return $treeBuilder;
    }
}