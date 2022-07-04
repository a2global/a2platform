<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\DependencyInjection;

use A2Global\A2Platform\Bundle\AdminBundle\Controller\AbstractAdminController;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AdminExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $this->getConfiguration($configs, $container);

        $container->registerForAutoconfiguration(AbstractAdminController::class)
            ->addTag('a2platform.controller.admin.resource');
    }
}