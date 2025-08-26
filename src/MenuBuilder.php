<?php

namespace Wiredupdev\MenuManagerBundle;

class MenuBuilder
{
    private array $menu = [
        'identifier' => null,
        'label' => null,
        'attributes' => [],
        'children' => [],
    ];

    public function __construct(string $identifier, string $label = '', ?string $uri = null)
    {
        $this->menu['identifier'] = $identifier;
        $this->menu['label'] = $label;
        $this->menu['uri'] = $uri;
    }

    public function createItem(string $identifier, string $label, ?string $uri = null): static
    {
        return new self($identifier, $label, $uri);
    }

    public function addAttribute(string $name, string $value): static
    {
        $this->menu['attributes'][$name] = $value;

        return $this;
    }

    public function addChild(self $menu): static
    {
        $this->menu['children'][] = $menu;

        return $this;
    }

    public function getMenu(): MenuItem
    {
        $menu = new MenuItem($this->menu['identifier'], $this->menu['label'], $this->menu['uri']);
        /** @var MenuBuilder $child */
        foreach ($this->menu['children'] as $child) {
            $menu->addChild($child->getMenu());
        }
        foreach ($this->menu['attributes'] as $name => $value) {
            $menu->addAttribute($name, $value);
        }

        return $menu;
    }
}
