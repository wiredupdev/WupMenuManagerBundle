<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\MenuItem;
use Wiredupdev\MenuManagerBundle\MenuManager;

#[CoversClass(MenuItem::class)]
class ManagerTest extends TestCase
{
    private MenuManager $menuManager;

    protected function setUp(): void
    {
        $this->menuManager = new MenuManager();
    }

    public function testAddMenu(): void
    {
        $menuBuilder = MenuItem::create('home_menu', '')
            ->addAttribute('id', 'id')
            ->addAttribute('class', 'class')
            ->addAttribute('role', 'role_anonymous_user')
            ->addChild(
                MenuItem::create('about_us', 'About us', 'https://example.com/about')
                    ->addAttribute('id', 'about-us')
                    ->addAttribute('role', 'role_anonymous_user')
            );

        $this->menuManager->add($menuBuilder);

        $this->assertTrue($this->menuManager->has('home_menu'));
    }

    public function testRemoveMenu(): void
    {
        $menuBuilder = MenuItem::create('home_menu', '')
            ->addAttribute('id', 'id')
            ->addAttribute('class', 'class')
            ->addAttribute('role', 'role_anonymous_user')
            ->addChild(
                MenuItem::create('about_us', 'About us', 'https://example.com/about')
                    ->addAttribute('id', 'about-us')
                    ->addAttribute('role', 'role_anonymous_user')
            );

        $this->menuManager->add($menuBuilder);

        $this->assertTrue($this->menuManager->has('home_menu'));

        $this->menuManager->remove('home_menu');

        $this->assertFalse($this->menuManager->has('home_menu'));
    }
}
