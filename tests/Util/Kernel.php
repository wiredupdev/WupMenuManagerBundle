<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Util;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wiredupdev\MenuManagerBundle\WupMenuManagerBundle;

class Kernel extends \Symfony\Component\HttpKernel\Kernel
{

    /**
     * @inheritDoc
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new WupMenuManagerBundle(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', [
                'test' => true,
            ]);
        });

        $loader->load(__DIR__ . '/../Resource/Config/wup_menu_manager.yaml');
        $loader->load(__DIR__ . '/../Resource/Config/wup_menu_manager.xml');

    }

    public function getProjectDir(): string
    {
        return __DIR__.'/../../' ;
    }


}