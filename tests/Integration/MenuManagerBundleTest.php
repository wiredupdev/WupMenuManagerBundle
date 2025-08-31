<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Integration;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Wiredupdev\MenuManagerBundle\MenuManager;
use Wiredupdev\MenuManagerBundle\WupMenuManagerBundle;

#[CoversClass(WupMenuManagerBundle::class)]
class MenuManagerBundleTest extends KernelTestCase
{
    public function testMenuManager() {
        static::bootKernel([
            'debug' => false,
        ]);

        $container = static::getContainer();

        $menuManager = $container->get(MenuManager::class);
        $this->assertInstanceOf(MenuManager::class, $menuManager);

    }
}