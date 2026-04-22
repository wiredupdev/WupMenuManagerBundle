<?php

namespace Wiredupdev\MenuManagerBundle\Menu\Processor;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\ProcessInterface;

readonly class UrlResolverProcessor implements ProcessInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function process(Item $item): void
    {
        if (false === isset($item->getOption('route')['name']) && null === $item->getUrl()) {
            return;
        }

        if (isset($item->getOption('route')['name'])) {
            $item->setUrl(
                $this->urlGenerator->generate(
                    $item->getOption('route')['name'],
                    $item->getOption('route')['parameters'] ?? [])
            );
        }

        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest->attributes->get('_route') === $item->getOption('route')
        || $currentRequest->getUri() === $item->getUrl()) {
            $item->activate();
        }
    }
}
