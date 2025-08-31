<?php

namespace Wiredupdev\MenuManagerBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class MenuManagerBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
       $container->import(__DIR__ . '/../config/services.xml');
    }


    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
              ->arrayNode('menu')
                ->children()
                ->enumNode('type')
                    ->values(['class'])
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
              ->end()
            ->end();
    }


}