<?php

namespace Wiredupdev\MenuManagerBundle\Menu\UriGenerator;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Wiredupdev\MenuManagerBundle\Menu\UriGeneratorInterface;

readonly class RouteLinkGenerator implements UriGeneratorInterface
{
    public function __construct(
        private string $name,
        private array $parameters,
        private string $target,
        private UrlGeneratorInterface $urlGenerator,
        private RequestStack $requestStack,
    ) {
    }

    public function generate(): string
    {
        return $this->urlGenerator->generate($this->name, $this->parameters);
    }

    public function isActive(): bool
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('_route') === $this->name;
    }

    public function getParams(): array
    {
        return $this->parameters;
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
