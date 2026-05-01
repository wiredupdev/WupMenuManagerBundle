<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Wiredupdev\MenuManagerBundle\Menu;
use Wiredupdev\MenuManagerBundle\Twig;

return static function (ContainerConfigurator $container): void {

    $services = $container->services();

    $services->set('wud_menu_manager', Menu\Manager::class);

    $services->set('wud_menu_manager.processor', Menu\Processor::class);

    $services->set('wud_menu_manager.twig_extension', Twig\MenuManagerExtension::class)
        ->args([
            '@wud_menu_manager',
            '@wud_menu_manager.processor',
            '@twig',
        ])
        ->tag('twig.extension');
};
