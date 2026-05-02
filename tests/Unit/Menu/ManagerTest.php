<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\Manager;
use Wiredupdev\MenuManagerBundle\Menu\MenuFactory;
use Wiredupdev\MenuManagerBundle\Menu\UriGeneratorInterface;

#[CoversClass(Item::class)]
class ManagerTest extends TestCase
{
    private Manager $menuManager;

    private UriGeneratorInterface $uriGenerator;

    private MenuFactory $menuFactory;

    protected function setUp(): void
    {
        $this->uriGenerator = $this->createStub(UriGeneratorInterface::class);

        $this->menuFactory = $this->createStub(MenuFactory::class);

        $this->uriGenerator->method('generate')
            ->willReturn('https://example.com/');

        $this->uriGenerator
            ->method('isActive')
            ->willReturn(true);

        $this->uriGenerator
            ->method('getTarget')
            ->willReturn('_self');

        $this->menuManager = new Manager();
        $this->menuManager->add(
            Item::create('admin_side_bar', '')
            ->addChild(Item::create('profile', 'Profile', $this->uriGenerator)
            ->addChild(Item::create('products', 'Products', $this->uriGenerator))
            ));
    }

    public function testConfigureLoadMenuClasses(): void
    {
        $this->menuManager->configure([
            'menu_classes' => [
                new class($this->menuManager, $this->menuFactory) {
                    public function __construct(
                        private Manager $menuManager,
                        private MenuFactory $menuFactory,
                    ) {
                    }
                    public function __invoke(): void
                    {
                        $this->menuFactory->create('');
                        $this->menuManager->add(Item::create('dashboard', 'Dashboard'));
                    }
                },
            ],
        ]);

        $this->assertTrue($this->menuManager->has('dashboard'));
    }

    public function testAddMenu(): void
    {
        $menuBuilder = Item::create('home_menu', '')

            ->addChild(
                Item::create('about_us', 'About us', $this->uriGenerator)
            );

        $this->menuManager->add($menuBuilder);

        $this->assertTrue($this->menuManager->has('home_menu'));
    }

    public function testRemoveMenu(): void
    {
        $menuBuilder = Item::create('home_menu', '')
            ->addChild(
                Item::create('about_us', 'About us', $this->uriGenerator)
            );

        $this->menuManager->add($menuBuilder);

        $this->assertTrue($this->menuManager->has('home_menu'));

        $this->menuManager->remove('home_menu');

        $this->assertFalse($this->menuManager->has('home_menu'));
    }
}
