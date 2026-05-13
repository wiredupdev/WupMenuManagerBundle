<?php

namespace Wiredupdev\MenuManagerBundle\Menu\UriGenerator;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
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

    public function createFromOptions(array $options): ?UriGeneratorInterface
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults([
            'target' => '_self',
            'link' => null,
            'route' => [
                'name' => null,
                'parameters' => [],
            ],
            'raw' => [],
        ]);

        $optionResolver->setAllowedTypes('target', ['string', 'null']);
        $optionResolver->setAllowedTypes('link', ['string', 'null']);
        $optionResolver->setAllowedTypes('route', ['array']);
        $optionResolver->setAllowedTypes('raw', ['array']);

        $optionResolver->setOptions('route', function (Options $options) {
            $options->setDefaults([
                'name' => null,
                'parameters' => [],
            ]);
            $options->setAllowedTypes('name', ['string', 'null']);
            $options->setAllowedTypes('parameters', ['array']);
        });

        $optionResolver->setOptions('raw', function (Options $options) {
            $options->setDefaults([
                'value' => null,
                'type' => null,
                'parameters' => [],
            ]);
            $options->setAllowedTypes('value', ['string', 'null']);
            $options->setAllowedTypes('type', ['string', 'null']);
            $options->setAllowedTypes('parameters', ['array']);
        });

        $options = $optionResolver->resolve($options);

        if ($options['raw']['value'] && ($type = GeneratorType::tryFrom($options['raw']['type']))) {
            return $this->create($type, $options['raw']['value'], [
                'parameters' => $options['raw']['parameters'],
                'target' => $options['target'],
            ]);
        }

        if ($options['link']) {
            return $this->create(GeneratorType::DIRECT_LINK_TYPE, $options['link'], [
                'target' => $options['target'],
            ]);
        }

        if ($options['route']['name']) {
            return $this->create(GeneratorType::ROUTE_LINK_TYPE, $options['route']['name'], [
                'parameters' => $options['route']['parameters'],
                'target' => $options['target'],
            ]);
        }

        return null;
    }
}
