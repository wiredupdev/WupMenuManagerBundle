<?php

namespace Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;

#[CoversClass(Item::class)]
class ItemTest extends TestCase
{
    private Item $menuItem;

    protected function setUp(): void
    {
        $this->menuItem = Item::create(
            'main_menu',
            'Main Menu', 'https://examplelink.com/'
        )
            ->addAttribute('rel', 'nofollow')
            ->addAttribute('target', '_blank');
    }

    public function testThrowsInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->menuItem->setId('Invalid id');
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
        $child = Item::create(
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
        $child = Item::create(
            'child_1',
            'Child 1',
            'https://examplelink2.com/',
        );

        $this->menuItem->addChild($child);

        $this->menuItem->removeChild('child_1');

        $this->assertTrue(null === $this->menuItem->getChild('child_1'));
    }

    /**
     * @throws \Exception
     */
    public function testNestedMenu(): void
    {
        $menuCopy = clone $this->menuItem;
        $menuCopy->setId('sub_menu');
        $menuCopy->setLabel('Submenu');
        $menuCopy->setUri('https://examplelink3.com/');

        $menuCopyCopy = clone $menuCopy;
        $menuCopy->addChild($menuCopyCopy);
        $this->menuItem->addChild($menuCopy);

        $menuArray = [
            'id' => 'main_menu',
            'label' => 'Main Menu',
            'uri' => 'https://examplelink.com/',
            'attributes' => [
                'target' => '_blank',
                'rel' => 'nofollow',
            ],
            'children' => [
                [
                    'id' => 'sub_menu',
                    'label' => 'Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                    ],
                    'children' => [
                        [
                            'id' => 'sub_menu',
                            'label' => 'Submenu',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_blank',
                                'rel' => 'nofollow',
                            ],
                            'children' => [],
                            'active_page' => false,
                            'enabled' => true
                        ],
                    ],
                    'active_page' => false,
                    'enabled' => true
                ],
            ],
            'active_page' => false,
            'enabled' => true
        ];

        $this->assertEquals($menuArray, $this->menuItem->toArray());
    }

    public function testFromArray()
    {
        $menu = Item::fromArray([
            'id' => 'main_menu',
            'label' => 'Main Menu',
            'children' => [
                [
                    'id' => 'sub_menu',
                    'label' => 'Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                    ],
                    'children' => [
                        [
                            'id' => 'sub_menu_1_2',
                            'label' => 'Submenu 1 2',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_blank',
                                'rel' => 'nofollow',
                            ],
                        ],
                    ],
                    'active_page' => false,
                    'enabled' => true
                ],
                [
                    'id' => 'sub_menu_2',
                    'label' => 'Submenu 2',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_new',
                        'rel' => 'nofollow',
                    ],
                    'active_page' => false,
                    'enabled' => true
                ],
            ],
            'active_page' => false,
            'enabled' => true
        ]);

        $this->assertEquals('main_menu', $menu->getId());
        $this->assertEquals('Main Menu', $menu->getLabel());
        $this->assertEquals('Submenu', $menu->getChild('sub_menu')->getLabel());
        $this->assertEquals('Submenu 2', $menu->getChild('sub_menu_2')->getLabel());
        $this->assertEquals('_new', $menu->getChild('sub_menu_2')->getAttribute('target'));
        $this->assertEquals('Submenu 1 2', $menu->getChild('sub_menu')->getChild('sub_menu_1_2')->getLabel());
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

    public function testSort(): void
    {
        $menu = Item::fromArray([
            'id' => 'main_menu',
            'label' => 'Main Menu',
            'children' => [
                [
                    'id' => 'sub_menu_b',
                    'label' => 'B Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                    ],
                    'children' => [
                        [
                            'id' => 'sub_menu_d',
                            'label' => 'd Submenu',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_blank',
                                'rel' => 'nofollow',
                            ],
                            'active_page' => false,
                            'enabled' => true
                        ],
                        [
                            'id' => 'sub_menu_c',
                            'label' => 'C Submenu',
                            'uri' => 'https://examplelink3.com/',
                            'attributes' => [
                                'target' => '_blank',
                                'rel' => 'nofollow',
                            ],
                            'active_page' => false,
                            'enabled' => true
                        ],
                    ],
                ],
                [
                    'id' => 'sub_menu_a',
                    'label' => 'A Submenu',
                    'uri' => 'https://examplelink3.com/',
                    'attributes' => [
                        'target' => '_blank',
                        'rel' => 'nofollow',
                    ],
                    'active_page' => false,
                    'enabled' => true
                ],
            ],
            'active_page' => false,
            'enabled' => true
        ]);

        $sortingByLabel = fn (Item $menuA, Item $menuB) => $menuA->getLabel() <=> $menuB->getLabel();
        $menu->sort($sortingByLabel);

        $this->assertSame(['sub_menu_a', 'sub_menu_b'], array_keys($menu->getIterator()->getArrayCopy()));
        $this->assertNotSame(['sub_menu_c', 'sub_menu_d'], array_keys($menu->getChild('sub_menu_b')->getIterator()->getArrayCopy()));

        $menu->sort($sortingByLabel, true);

        $this->assertSame(['sub_menu_c', 'sub_menu_d'], array_keys($menu->getChild('sub_menu_b')->getIterator()->getArrayCopy()));
    }
}
