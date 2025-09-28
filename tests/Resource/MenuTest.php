<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Resource;

use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\Manager;

class MenuTest
{
    public function __invoke(Manager $menuManager): void
    {
        $menuManager->add(
            Item::create('main_menu_site', 'main menu site')
            ->addChild(
                Item::create('main_menu_home', 'home')
                ->addAttribute('class', 'main-menu-home')
            )->addChild(
                Item::create('main_menu_about_about_us', 'about us')
                ->addAttribute('class', 'main-menu-about-about-us')
            )
            ->addChild(
                Item::create('main_menu_contact_us', 'Contact us')
                ->addAttribute('class', 'main-menu-contact-us')
            )
            ->addChild(
                Item::create('main_menu_product', 'Products')
                ->addAttribute('class', 'main-menu-products')
                ->addChild(
                    Item::create('main_menu_product_a', 'Product a')
                    ->addAttribute('class', 'main-menu-products-a')
                )
                ->addChild(
                    Item::create('main_menu_product_b', 'Product b')
                    ->addAttribute('class', 'main-menu-products-b')
                )
                ->addChild(
                    Item::create('main_menu_product_c', 'Product c')
                    ->addAttribute('class', 'main-menu-products-c')
                )
            )
        );
    }
}
