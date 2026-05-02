<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Wiredupdev\MenuManagerBundle\Menu;
use Wiredupdev\MenuManagerBundle\Twig;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set(Menu\Manager::class)
        ->alias('wud_menu_manager', Menu\Manager::class);

    $services->set(Menu\Processor::class)
        ->alias('wud_menu_manager.processor', Menu\Processor::class);

    $services->set(Twig\MenuManagerExtension::class)
        ->arg('$menuManager', service('wud_menu_manager'))
        ->arg('$processor', service('wud_menu_manager.processor'))
        ->arg('$twig', service('twig'))
        ->tag('twig.extension')
        ->alias(Twig\MenuManagerExtension::class, 'wud_menu_manager.twig_extension');

    $services->set(Menu\CachedProcessor::class)
        ->decorate('wud_menu_manager.processor')
        ->arg('$processor', service('.inner'))
        ->alias(Menu\CachedProcessor::class, 'wud_menu_manager.processor.cached');

    $services->set('wud_menu_factory', Menu\MenuFactory::class)
             ->alias('Menu\Factory', 'wud_menu_factory');
};
