<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\UriGeneratorInterface;

#[CoversClass(Item::class)]
class ItemTest extends TestCase
{
    public function testAddChild(): void
    {
        $menu = Item::create('main_menu', '')
            ->addChild(
                Item::create('home', 'Home')
            );

        $this->assertInstanceOf(Item::class, $menu->getChild('home'));
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

    public function testUriGenerator(): void
    {
        $urlGenerator = $this->createMock(UriGeneratorInterface::class);

        $urlGenerator->expects($this->once())
            ->method('generate')
            ->willReturn('https://example.com/');

        $urlGenerator->expects($this->once())
            ->method('isActive')
            ->willReturn(true);

        $urlGenerator->expects($this->atLeast(0))
            ->method('getTarget')
            ->willReturn('_self');

        $menu = Item::create('main_menu', '')
            ->addChild(
                Item::create('home', 'Home', $urlGenerator)
            );

        $this->assertInstanceOf(Item::class, $menu->getChild('home'));
        $this->assertEquals('https://example.com/', $menu->getChild('home')->getUri());
        $this->assertEquals('_self', $menu->getChild('home')->getUriTarget());
        $this->assertTrue($menu->getChild('home')->isActive());
    }

    public function testThrowsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Item::create('invalid identifier', '!invalid identifier');
    }
}
