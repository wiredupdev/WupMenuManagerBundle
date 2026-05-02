<?php

namespace Wiredupdev\MenuManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Wiredupdev\MenuManagerBundle\Menu\Processor;

class ProcessPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $processes = [];
        foreach ($container->findTaggedServiceIds('wud_menu_manager.processes', true) as $serviceId => $tagAttributes) {
            $processes[] = new Reference($serviceId);
        }
        $container->getDefinition(Processor::class)->setArgument('$processes', $processes);
    }
}
