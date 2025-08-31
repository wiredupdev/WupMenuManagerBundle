<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\MenuItem;

#[CoversClass(MenuItem::class)]
class MenuItemTest extends TestCase
{
    private MenuItem $menuItem;

    protected function setUp(): void
    {
        $this->menuItem = MenuItem::create(
            'main_menu',
            'Main', 'https://examplelink.com/'
        );

        $this->menuItem->addAttribute('rel', 'nofollow');
        $this->menuItem->addAttribute('target', '_blank');
    }

    public function testInvalidIdentifierThrowsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->menuItem->setIdentifier('Invalid identifier');
    }

    public function testGetAttribute(): void
    {
        $target = $this->menuItem->getAttribute('target');

        $this->assertEquals('_blank', $target);
    }

    public function testRemoveAttribute(): void
    {
        $this->menuItem->removeAttribute('rel');

        $this->assertTrue(null === $this->menuItem->getAttribute('rel'));
    }

    public function testAddChild(): void
    {
        $child = MenuItem::create(
            'child_1',
            'Child 1',
            'https://examplelink2.com/'
        );

        $child->addAttribute('rel', 'nofollow');
        $child->addAttribute('target', '_blank');

        $this->menuItem->addChild($child);

        $this->assertTrue($this->menuItem->getChild('child_1') === $child);
    }

    public function testRemoveChild(): void
    {
        $child = MenuItem::create(
            'child_1',
            'Child 1',
            'https://examplelink2.com/',
        );

        $child->addAttribute('rel', 'nofollow');
        $child->addAttribute('target', '_blank');

        $this->menuItem->addChild($child);

        $menuChild = $this->menuItem->getChild('child_1');

        $this->menuItem->removeChild($menuChild);

        $this->assertTrue(null === $this->menuItem->getChild('child_1'));
    }

    public function testNestedMenu(): void
    {
        $menuCopy = clone $this->menuItem;
        $menuCopy->setIdentifier('sub_menu');
        $menuCopy->setLabel('Submenu');
        $menuCopy->setUri('https://examplelink3.com/');

        $menuCopyCopy = clone $menuCopy;
        $menuCopy->addChild($menuCopyCopy);
        $this->menuItem->addChild($menuCopy);

        $menuArray = [
            'identifier' => 'main_menu',
            'label' => 'Main Menu',
            'uri' => 'https://examplelink.com/',
            'attributes' => [
                'target' => '_blank',
                'rel' => 'nofollow',
            ],
            'children' => [
                [
                    'identifier' => 'sub_menu',
                    'label' => 'Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                    ],
                    'children' => [
                        [
                            'identifier' => 'sub_menu',
                            'label' => 'Submenu',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_blank',
                                'rel' => 'nofollow',
                            ],
                            'children' => [],
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($menuArray, $this->menuItem->toArray());
    }

    public function testFromArray()
    {
        $menu = MenuItem::fromArray([
            'identifier' => 'main_menu',
            'label' => 'Main Menu',
            'children' => [
                [
                    'identifier' => 'sub_menu',
                    'label' => 'Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                    ],
                    'children' => [
                        [
                            'identifier' => 'sub_menu_1_2',
                            'label' => 'Submenu 1 2',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_new',
                                'rel' => 'nofollow',
                            ],
                        ],
                    ],
                ],
                [
                    'identifier' => 'sub_menu_2',
                    'label' => 'Submenu 2',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_new',
                        'rel' => 'nofollow',
                    ],
                ],
            ],
        ]);

        $this->assertEquals('main_menu', $menu->getIdentifier());
        $this->assertEquals('Main Menu', $menu->getLabel());
        $this->assertEquals('Submenu', $menu->getChild('sub_menu')->getLabel());
        $this->assertEquals('Submenu 2', $menu->getChild('sub_menu_2')->getLabel());
        $this->assertEquals('_new', $menu->getChild('sub_menu_2')->getAttribute('target'));
        $this->assertEquals('Submenu 1 2', $menu->getChild('sub_menu')->getChild('sub_menu_1_2')->getLabel());
    }

    public function testFromArrayThrowsInvalidArgumentExceptionWhenMissingIdentifier(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        MenuItem::fromArray([
            'label' => 'Menu',
            'children' => [],
            'parent' => null,
        ]);
    }

    public function testSort(): void
    {
        $menu = MenuItem::fromArray([
            'identifier' => 'main_menu',
            'label' => 'Main Menu',
            'children' => [
                [
                    'identifier' => 'sub_menu_b',
                    'label' => 'B Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                    ],
                    'children' => [
                        [
                            'identifier' => 'sub_menu_d',
                            'label' => 'd Submenu',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_new',
                                'rel' => 'nofollow',
                            ],
                        ],
                        [
                            'identifier' => 'sub_menu_c',
                            'label' => 'C Submenu',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_new',
                                'rel' => 'nofollow',
                            ],
                        ],
                    ],
                ],
                [
                    'identifier' => 'sub_menu_a',
                    'label' => 'A Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_new',
                        'rel' => 'nofollow',
                    ],
                ],
            ],
        ]);

        $sortingByLabel = fn (MenuItem $menuA, MenuItem $menuB) => $menuA->getLabel() <=> $menuB->getLabel();
        $menu->sort($sortingByLabel);

        $this->assertSame(['sub_menu_a', 'sub_menu_b'], array_keys($menu->getIterator()->getArrayCopy()));
        $this->assertNotSame(['sub_menu_c', 'sub_menu_d'], array_keys($menu->getChild('sub_menu_b')->getIterator()->getArrayCopy()));

        $menu->sort($sortingByLabel, true);

        $this->assertSame(['sub_menu_c', 'sub_menu_d'], array_keys($menu->getChild('sub_menu_b')->getIterator()->getArrayCopy()));
    }
}
