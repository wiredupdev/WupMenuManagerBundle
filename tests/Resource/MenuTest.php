<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Resource;

use Wiredupdev\MenuManagerBundle\Menu\MenuFactory;
use Wiredupdev\MenuManagerBundle\Menu\MenuItemInterface;

class MenuTest
{
    public function __construct(
        private MenuFactory $menuFactory,
    ) {
    }

    public function __invoke(): MenuItemInterface
    {
        return $this->menuFactory->create('main_menu_site', [
            'children' => [
                [
                    'label' => 'Home',
                    'url' => 'https://www.example.com/home',
                ],
            ],
        ]);
    }
}
