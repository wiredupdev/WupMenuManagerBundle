<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;

#[CoversClass(Item::class)]
class ItemTest extends TestCase
{
    public function testAddChild(): void
    {
        $menu = Item::create('main_menu', '')
            ->addChild(
                Item::create('home', 'Home', ['uri' => 'http://example.com/home']),
            )->addChild(
                Item::create('products', 'Products', ['route' => 'app_products'])
                    ->addChild(
                        Item::create(
                            'product_one', 'Product One',
                            ['route' => 'app_product', 'parameters' => ['name' => 'one']])
                    )
            );

        $this->assertInstanceOf(Item::class, $menu->getChild('home'));
        $this->assertEquals('http://example.com/home', $menu->getChild('home')->getUrl());
    }

    public function testAddAttribute(): void
    {
        $menu = Item::create('main_menu', '')
            ->addAttribute('html', 'class', 'bg-gray-100')
            ->addAttribute('security', 'roles', ['ROLE_ADMIN']);

        $this->assertEquals('bg-gray-100', $menu->getAttribute('html', 'class'));
        $this->assertEquals(['ROLE_ADMIN'], $menu->getAttribute('security', 'roles'));
    }

    public function testRemoveAttribute(): void
    {
        $menu = Item::create('main_menu', '')
            ->addAttribute('html', 'class', 'bg-gray-100')
            ->addAttribute('security', 'roles', ['ROLE_ADMIN']);

        $menu->removeAttribute('security', 'roles');

        $this->assertEquals('bg-gray-100', $menu->getAttribute('html', 'class'));
        $this->assertFalse($menu->hasAttribute('security', 'roles'));
    }

    public function testRemoveChild(): void
    {
        $menu = Item::create('main_menu', 'root')
            ->addChild(
                Item::create('home', 'Home')
            );
        $menu->removeChild('home');
        $this->assertNull($menu->getChild('home'));
    }

    public function testThrowsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Item::create('invalid identifier', '!invalid identifier');
    }
}
