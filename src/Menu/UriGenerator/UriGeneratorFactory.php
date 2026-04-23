<?php

namespace Wiredupdev\MenuManagerBundle\Menu\UriGenerator;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Wiredupdev\MenuManagerBundle\Menu\UriGeneratorInterface;

class UriGeneratorFactory
{
    public function __construct(
        private UrlGeneratorInterface $uriGenerator,
        private RequestStack $requestStack,
    ) {
    }

    public function create(GeneratorType $type, string $uri, array $options = []): UriGeneratorInterface
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults([
            'target' => '_self',
            'parameters' => [],
        ]);

        $options = $optionResolver->resolve($options);

        return match ($type) {
            GeneratorType::DIRECT_LINK_TYPE => new DirectLinkGenerator($uri, $options['target'], $this->requestStack),
            GeneratorType::ROUTE_LINK_TYPE => new RouteLinkGenerator($uri, $options['parameters'], $options['target'], $this->uriGenerator, $this->requestStack),
        };
    }
}
