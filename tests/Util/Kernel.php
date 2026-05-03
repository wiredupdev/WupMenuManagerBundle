<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Util;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Wiredupdev\MenuManagerBundle\WudMenuManagerBundle;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new WudMenuManagerBundle(),
        ];
    }

    /**
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'router' => [
                    'resource' => 'kernel::loadRoutes',
                    'type' => 'service',
                ],
                'test' => true,
            ]);

            $loader = new YamlFileLoader(
                $container,
                new FileLocator(__DIR__.'/../Resource/Config')
            );

            $loader->load('services.yaml');
        });
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new class implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                if ($container->hasDefinition('router')) {
                    $container->getDefinition('router')->setPublic(true);
                }
            }
        });
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }
}
