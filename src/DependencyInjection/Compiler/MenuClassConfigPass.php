<?php

namespace Wiredupdev\MenuManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MenuClassConfigPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $configs = ['menu_classes' => []];
        foreach ($container->findTaggedServiceIds('wud_menu_manager.menus', true) as $serviceId => $tagAttributes) {
            $configs['menu_classes'][] = new Reference($serviceId);
        }

        $container->getDefinition('wud_menu_manager')->setArgument('$configs', $configs);
    }
}
