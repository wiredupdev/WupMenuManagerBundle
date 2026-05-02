<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Resource;

use Wiredupdev\MenuManagerBundle\Menu\Manager;
use Wiredupdev\MenuManagerBundle\Menu\MenuFactory;

class MenuTest
{
    public function __construct(
        private Manager $menuManager,
        private MenuFactory $menuFactory,
    ) {
    }

    public function __invoke(): void
    {
        $this->menuManager->add(
            $this->menuFactory->create('main_menu_site', [
                'children' => [
                    [
                        'label' => 'Home',
                        'url' => 'https://www.example.com/home',
                    ],
                ],
            ])
        );
    }
}
