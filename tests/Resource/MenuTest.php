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
                Item::create('main_menu_home', 'home', 'http://localhost/home')
                ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'item_html_class', 'main-menu-home')
            )->addChild(
                Item::create('main_menu_about_about_us', 'about us', 'http://localhost/aboutus')
                ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'item_html_class', 'main-menu-about-about-us')
            )
            ->addChild(
                Item::create('main_menu_contact_us', 'Contact us', 'http://localhost/contactus')
                ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'item_html_class', 'main-menu-contact-us')
            )
            ->addChild(
                Item::create('main_menu_product', 'Products', 'http://localhost/products')
                ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'item_html_class', 'main-menu-products')
                ->addChild(
                    Item::create('main_menu_product_a', 'Product a', 'http://localhost/products/a')
                    ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'item_html_class', 'main-menu-products-a')
                )
                ->addChild(
                    Item::create('main_menu_product_b', 'Product b', 'http://localhost/products/b')
                    ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'item_html_class', 'main-menu-products-b')
                )
                ->addChild(
                    Item::create('main_menu_product_c', 'Product c', 'http://localhost/products/c')
                    ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'item_html_class', 'main-menu-products-c')
                )
            )
        );
    }
}
