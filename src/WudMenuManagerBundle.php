<?php

namespace Wiredupdev\MenuManagerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Wiredupdev\MenuManagerBundle\Cache\MenuItemMarshaller;
use Wiredupdev\MenuManagerBundle\DependencyInjection\Compiler\MenuClassConfigPass;
use Wiredupdev\MenuManagerBundle\DependencyInjection\Compiler\ProcessPass;

class WudMenuManagerBundle extends AbstractBundle implements PrependExtensionInterface
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

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'cache' => [
                'pools' => [
                    'wud_menu_manager.cache_pool' => [
                        'marshaller' => MenuItemMarshaller::class,
                        'default_lifetime' => 0,
                    ],
                ],
            ],
        ]);
    }
}
