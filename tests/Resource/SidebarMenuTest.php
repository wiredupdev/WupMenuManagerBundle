<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Resource;

use Wiredupdev\MenuManagerBundle\MenuManager;
use Wiredupdev\MenuManagerBundle\MenuManager\MenuItem;

class SidebarMenuTest
{
    public function __invoke(MenuManager $menuManager): void
    {
        $menuManager->add(
            MenuItem::create('main_menu_site', 'main menu site')
            ->addChild(
                MenuItem::create('main_menu_home', 'home')
                ->addAttribute('class', 'main-menu-home')
            )->addChild(
                MenuItem::create('main_menu_about_about_us', 'about us')
                ->addAttribute('class', 'main-menu-about-about-us')
            )
            ->addChild(
                MenuItem::create('main_menu_contact_us', 'Contact us')
                ->addAttribute('class', 'main-menu-contact-us')
            )
            ->addChild(
                MenuItem::create('main_menu_product', 'Products')
                ->addAttribute('class', 'main-menu-products')
                ->addChild(
                    MenuItem::create('main_menu_product_a', 'Product a')
                    ->addAttribute('class', 'main-menu-products-a')
                )
                ->addChild(
                    MenuItem::create('main_menu_product_b', 'Product b')
                    ->addAttribute('class', 'main-menu-products-b')
                )
                ->addChild(
                    MenuItem::create('main_menu_product_c', 'Product c')
                    ->addAttribute('class', 'main-menu-products-c')
                )
            )
        );
    }
}
