<?php

namespace Wiredupdev\MenuManagerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Wiredupdev\MenuManagerBundle\DependencyInjection\Compiler\MenuClassConfigPass;
use Wiredupdev\MenuManagerBundle\DependencyInjection\Compiler\ProcessPass;

class WudMenuManagerBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MenuClassConfigPass());
        $container->addCompilerPass(new ProcessPass());
    }

    /**
     * @throws \Exception
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
    }
}
