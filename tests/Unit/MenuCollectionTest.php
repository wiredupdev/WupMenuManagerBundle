<?php

namespace Wiredupdev\MenuManager\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\MenuCollection;
use Wiredupdev\MenuManagerBundle\MenuItem;

#[CoversClass(MenuCollection::class)]
class MenuCollectionTest extends TestCase
{
    private MenuCollection  $menuCollection;
    protected function setUp() : void
    {
        $this->menuCollection = new MenuCollection();
    }

    public static function menuDataProvider(): \Generator
    {
        $menu = new MenuItem('main_menu', '', null);
        $menu->addChild(new MenuItem('home', 'home', 'http://example.com/'));
        $menu->addChild(new MenuItem('about_us', 'About us', 'http://example.com/about-us'));
        $menu->addChild(new MenuItem('contact_us', 'Contact us', 'http://example.com/contact-us'));
        yield [$menu];
    }


    #[DataProvider('menuDataProvider')]
    public function testAdd(MenuItem $mainMenu): void
    {
        $this->menuCollection->add($mainMenu);
        $this->assertCount(1, $this->menuCollection);
    }

    #[DataProvider('menuDataProvider')]
    public function testAddThrowsExceptionWhenIdentifiersExists(MenuItem $mainMenu): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->menuCollection->add($mainMenu);
        $this->menuCollection->add($mainMenu);
    }

    #[DataProvider('menuDataProvider')]
    public function testHasMenuWithIdentifier(MenuItem $mainMenu): void
    {
        $this->menuCollection->add($mainMenu);
        $this->assertTrue($this->menuCollection->has('main_menu'));
    }

    #[DataProvider('menuDataProvider')]
    public function testGetMenu(MenuItem $mainMenu): void
    {
        $this->menuCollection->add($mainMenu);
        $this->assertInstanceOf(MenuItem::class, $this->menuCollection->get('main_menu'));
        $this->assertEquals($mainMenu->getIdentifier(), $this->menuCollection->get('main_menu')->getIdentifier());
    }

    #[DataProvider('menuDataProvider')]
    public function testRemove(MenuItem $mainMenu): void
    {
        $this->menuCollection->add($mainMenu);
        $this->menuCollection->remove('main_menu');
        $this->assertFalse($this->menuCollection->has('main_menu'));
    }

}
