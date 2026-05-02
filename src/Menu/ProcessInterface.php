<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

interface ProcessInterface
{
    public function process(MenuItemInterface $menuItem): void;
}
