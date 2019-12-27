<?php


namespace Symka\Core\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SymkaCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        foreach ($configs as $config) {
           if (isset($config["admin_vertical_menu"])) {
               $container->setParameter('admin_vertical_menu', $config['admin_vertical_menu']);
           }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resource/config'));
        $loader->load('services.yaml');
      //  $loader->load('validation.yaml');
    }
}