<?php

namespace Wiredupdev\MenuManagerBundle\Menu\UriGenerator;

use Symfony\Component\HttpFoundation\RequestStack;
use Wiredupdev\MenuManagerBundle\Menu\UriGeneratorInterface;

readonly class DirectLinkGenerator implements UriGeneratorInterface
{
    public function __construct(
        private string $uri,
        private string $target,
        private RequestStack $requestStack,
    ) {
    }

    public function generate(): string
    {
        return $this->uri;
    }

    public function isActive(): bool
    {
        return $this->requestStack->getCurrentRequest()->getUri() === $this->uri;
    }

    public function getParams(): array
    {
        return [];
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
