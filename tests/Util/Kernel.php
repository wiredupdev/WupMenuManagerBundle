<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Util;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
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
                'test' => true,
            ]);

            $loader = new YamlFileLoader(
                $container,
                new FileLocator(__DIR__.'/../Resource/Config')
            );

            $loader->load('services.yaml');
        });
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }
}
