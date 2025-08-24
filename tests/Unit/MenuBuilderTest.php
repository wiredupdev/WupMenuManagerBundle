<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\MenuBuilder;
use Wiredupdev\MenuManagerBundle\MenuItem;

#[CoversClass(MenuBuilder::class)]
class MenuBuilderTest extends TestCase
{
    private MenuBuilder $menuBuilder;

    protected function setUp(): void
    {
        $menuBuilder = new MenuBuilder('main_menu');

        $menuBuilder
        ->addAttribute('id', 'main_menu')
        ->addAttribute('title', 'main_menu')
        ->addAttribute('class', 'main_menu')
        ->addChild(
            $menuBuilder->createItem('home', 'Home', 'home_route')
            ->addAttribute('id', 'home')
            ->addAttribute('title', 'home')
            ->addAttribute('class', 'home')
        )->addChild(
            $menuBuilder->createItem('about', 'About', 'about_route')
            ->addAttribute('id', 'about')
            ->addAttribute('title', 'about')
            ->addAttribute('class', 'about')
        )->addChild(
            $menuBuilder->createItem('products', 'products', 'products_route')
                ->addAttribute('id', 'products')
                ->addAttribute('title', 'products')
                ->addAttribute('class', 'products')
                ->addChild(
                    $menuBuilder->createItem('product_one', 'product one', 'product_one_route')
                        ->addAttribute('id', 'product_one')
                        ->addAttribute('title', 'product_one')
                        ->addAttribute('class', 'product-one')
                )->addChild(
                    $menuBuilder->createItem('product_two', 'product two', 'product_two_route')
                        ->addAttribute('id', 'product_two')
                        ->addAttribute('title', 'product two')
                        ->addAttribute('class', 'product-two')
                )
        );

        $this->menuBuilder = $menuBuilder;
    }

    public function testGetMenuReturnMenuItemObject(): void
    {
        $this->assertInstanceOf(MenuItem::class, $this->menuBuilder->getMenu());
    }

    public function testAddNestedMenu(): void
    {
        $this->assertIsArray($this->menuBuilder->getMenu()->toArray());
    }
}
