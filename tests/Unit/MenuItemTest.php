<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\MenuItem;

#[CoversClass(MenuItem::class)]
class MenuItemTest extends TestCase
{
    private MenuItem $menuItem;

    public function setUp(): void
    {
        $this->menuItem = new MenuItem(
            'main_menu',
            'Main Menu',
            'https://examplelink.com/',
            [
                'target' => '_blank',
                'rel' => 'nofollow',
            ]
        );
    }

    public function testGetAttribute(): void
    {
        $target = $this->menuItem->getAttribute('target');

        $this->assertEquals('_blank', $target);
    }

    public function testRemoveAttribute(): void
    {
        $this->menuItem->removeAttribute('rel');

        $this->assertTrue(($this->menuItem->getAttribute('rel') === null));
    }

    public function testShouldNotRemoveStaticAttributes(): void
    {
        $this->menuItem->removeAttribute('_identifier');

        $this->assertFalse(($this->menuItem->getAttribute('_identifier') === null));
    }


    public function testAddChild(): void
    {
        $child = new MenuItem(
            'child_1',
            'Child 1',
            'https://examplelink2.com/',
            [
                'rel' => 'nofollow',
                'target' => 'self',
            ]
        );

        $this->menuItem->addChild($child);

        $this->assertTrue(($this->menuItem->getChild(0) === $child));
    }

    public function testRemoveChild(): void
    {

        $child = new MenuItem(
            'child_1',
            'Child 1',
            'https://examplelink2.com/',
            [
                'rel' => 'nofollow',
                'target' => 'self',
            ]
        );

        $this->menuItem->addChild($child);

        $menuChild = $this->menuItem->getChild(0);

        $this->menuItem->removeChild($menuChild);

        $this->assertTrue(($this->menuItem->getChild(0) === null));
    }
}