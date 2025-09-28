<?php

namespace Wiredupdev\MenuManagerBundle\Menu\Processor;

use Wiredupdev\MenuManagerBundle\Menu\Item;

interface ProcessInterface
{
    public function process(Item $item);
}
