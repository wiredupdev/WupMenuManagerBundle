<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Integration\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Wiredupdev\MenuManagerBundle\Menu\Manager;
use Wiredupdev\MenuManagerBundle\WupMenuManagerBundle;

#[CoversClass(WupMenuManagerBundle::class)]
class ManagerBundleTest extends KernelTestCase
{
    public function testMenuManager()
    {
        static::bootKernel([
            'debug' => false,
        ]);

        $container = static::getContainer();

        $menuManager = $container->get(Manager::class);
        $this->assertInstanceOf(Manager::class, $menuManager);
    }
}
