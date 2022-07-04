<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class CoreExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $this->getConfiguration($configs, $container);
    }

    public function prepend(ContainerBuilder $container)
    {
        $yml = Yaml::parseFile(__DIR__ . '/../Resources/config/config.yml');

        foreach ($yml as $package => $config) {
            $container->prependExtensionConfig($package, $config);
        }
    }
}