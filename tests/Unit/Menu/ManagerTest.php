<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\Manager;

#[CoversClass(Item::class)]
class ManagerTest extends TestCase
{
    private Manager $menuManager;

    protected function setUp(): void
    {
        $this->menuManager = new Manager();
        $this->menuManager->add(
            Item::create('admin_side_bar', '')
            ->addChild(Item::create('profile', 'Profile', 'https://example.com/profile'))
            ->addChild(Item::create('products', 'Products', 'https://example.com/products'))
        );
    }

    public function testConfigureLoadMenuClasses(): void
    {
        $this->menuManager->configure([
            'menu_classes' => [
                new class {
                    public function __invoke(Manager $manager): void
                    {
                        $manager->add(Item::create('dashboard', 'Dashboard', 'https://example.com/admin/dashboard'));
                    }
                },
            ],
        ]);

        $this->assertTrue($this->menuManager->has('dashboard'));
    }

    public function testModifyExistingMenuItem(): void
    {
        $this->menuManager->get('admin_side_bar')->getChild('products')->setPosition(1);
        $this->menuManager->get('admin_side_bar')->getChild('profile')->setPosition(2);

        $menu = $this->menuManager->get('admin_side_bar')->sortByPosition(true);

        $this->assertSame(['products', 'profile'], array_keys($menu->getIterator()->getArrayCopy()));
    }

    public function testAddMenu(): void
    {
        $menuBuilder = Item::create('home_menu', '')
            ->addAttribute(Item\AttributeType::ITEM_CONTAINER,'id', 'id')
            ->addAttribute(Item\AttributeType::ITEM_CONTAINER,'class', 'class')
            ->addAttribute(Item\AttributeType::ITEM_CONTAINER,'role', 'role_anonymous_user')
            ->addChild(
                Item::create('about_us', 'About us', 'https://example.com/about')
                    ->addAttribute(Item\AttributeType::ITEM_CONTAINER, 'id', 'about-us')
            );

        $this->menuManager->add($menuBuilder);

        $this->assertTrue($this->menuManager->has('home_menu'));
    }

    public function testRemoveMenu(): void
    {
        $menuBuilder = Item::create('home_menu', '')
            ->addAttribute(Item\AttributeType::ITEM_CONTAINER,'id',  'id')
            ->addAttribute(Item\AttributeType::ITEM_CONTAINER,'class', 'class')
            ->addChild(
                Item::create('about_us', 'About us', 'https://example.com/about')
                    ->addAttribute(Item\AttributeType::ITEM_CONTAINER,'id', 'about-us')
                    ->setRoles(['role_anonymous_user'])
            );

        $this->menuManager->add($menuBuilder);

        $this->assertTrue($this->menuManager->has('home_menu'));

        $this->menuManager->remove('home_menu');

        $this->assertFalse($this->menuManager->has('home_menu'));
    }
}
