<?php

namespace Wiredupdev\MenuManagerBundle\Menu\Processor;

use Symfony\Component\HttpFoundation\RequestStack;
use Wiredupdev\MenuManagerBundle\Menu\Item;

class UriActivationProcess implements ProcessInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function process(Item $item): void
    {
        if ($item->getUri() === $this->requestStack->getCurrentRequest()->getUri()) {
            $item->activate();
        }
    }
}
