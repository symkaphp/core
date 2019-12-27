<?php


namespace Symka\Core;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymkaCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->registerForAutoconfiguration(EntityRepository::class)
            ->addTag('doctrine.repository_service');
    }
}