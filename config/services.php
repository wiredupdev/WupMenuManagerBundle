<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\RequestStack;
use Wiredupdev\MenuManagerBundle\Cache\MenuItemMarshaller;
use Wiredupdev\MenuManagerBundle\Menu;
use Wiredupdev\MenuManagerBundle\Menu\UriGenerator\UriGeneratorFactory;
use Wiredupdev\MenuManagerBundle\Twig;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('wud_menu_manager.cache_pool', FilesystemAdapter::class)
        ->args(['wud_menu_manager', 0, '%kernel.cache_dir%/wud_menu_manager',  service(MenuItemMarshaller::class)])
        ->tag('cache.pool', [
            'clearer' => 'cache.default_clearer',
            'pruneable' => 'cache.default_pruner',
        ]);

    $services->set(UriGeneratorFactory::class)
        ->arg('$uriGenerator', service('router'))
        ->arg('$requestStack', service(RequestStack::class));

    $services->set(Menu\Manager::class);

    $services->set(Menu\Processor::class);

    $services->set(Twig\MenuManagerExtension::class)
        ->arg('$menuManager', service('wud_menu_manager'))
        ->arg('$processor', service('wud_menu_manager.processor'))
        ->arg('$twig', service('twig'))
        ->tag('twig.extension');

    $services->set(Menu\CachedProcessor::class)
        ->decorate('wud_menu_manager.processor')
        ->arg('$cache', service('wud_menu_manager.cache_pool'))
        ->arg('$processor', service('.inner'));

    $services->set(Menu\MenuFactory::class)
        ->arg('$uriGeneratorFactory', service(UriGeneratorFactory::class))
        ->alias('wud_menu_factory', Menu\MenuFactory::class);

    $services->set(MenuItemMarshaller::class)
        ->arg('$menuFactory', service(Menu\MenuFactory::class))
        ->tag('wud.menu_marshaller');

    $services
        ->alias('wud_menu_manager', Menu\Manager::class)
        ->alias('wud_menu_manager.processor', Menu\Processor::class)
        ->alias('wud_menu_manager.processor.cached', Menu\CachedProcessor::class)
        ->alias('wud_menu_manager.twig_extension', Twig\MenuManagerExtension::class);
};
