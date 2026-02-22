<?php

namespace  Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;

#[CoversClass(Item::class)]
class ItemTest extends TestCase
{
    public function testAddAttribute(): void
    {
        $menu = Item::create('main_menu', 'main menu')
            ->addAttribute('class', 'some-class');

        $this->assertSame('some-class', $menu->getAttribute('class'), "Attribute 'class' wasn't set correctly.");
    }

    public function testRemoveAttribute(): void
    {
        $menu = Item::create('main_menu', 'main menu')
            ->addAttribute('id', 'my_id');

        $menu->removeAttribute('id');

        $this->assertFalse($menu->hasAttribute('id'), "Attribute 'class' wasn't removed correctly.");
    }

    public function testAddChild(): void
    {
        $menu = Item::create('main_menu', 'root')
            ->addChild(
                Item::create('home', 'Home')
            );

        $this->assertInstanceOf(Item::class, $menu->getChild('home'));
    }

    public function testFromArray(): void
    {
        $menu = Item::fromArray([
            'id' => 'main_menu',
            'label' => 'main menu',
            'children' => [
                [
                    'id' => 'home',
                    'label' => 'home',
                    'uri' => 'https://www.example.com/',
                ],
            ],
        ]);

        $this->assertSame('main_menu', $menu->getId());
    }

    /**
     * @throws \Exception
     */
    public function testSortingByPosition(): void
    {
        $menu = Item::fromArray([
            'id' => 'main_menu',
            'label' => 'main menu',
            'children' => [
                [
                    'id' => 'about_us',
                    'label' => 'About Us',
                    'uri' => 'https://www.example.com/about-us/',
                ],
                [
                    'id' => 'home',
                    'label' => 'home',
                    'uri' => 'https://www.example.com/',
                ],
            ],
        ]);

        $menu->getChild('home')->setPosition(1);
        $menu->getChild('about_us')->setPosition(2);

        $menu->sortByPosition(true);
        $this->assertSame(['home', 'about_us'], array_keys($menu->getIterator()->getArrayCopy()), 'Children are not in the correct order.');
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
        Item::create('invalid identifier', '');
    }

    public function testFromArrayThrowsInvalidArgumentExceptionWhenMissingId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Item::fromArray([
            'label' => 'Menu',
            'children' => [],
            'parent' => null,
        ]);
    }
}
