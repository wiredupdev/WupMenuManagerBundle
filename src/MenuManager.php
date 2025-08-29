<?php

namespace Wiredupdev\MenuManagerBundle;

class MenuManager
{
    private array $menus = [];

    public function get(string $identifier): MenuItem
    {
        if (($menu = $this->menus[$identifier]) === null) {
            throw new \InvalidArgumentException(\sprintf("Menu with identifier '%s' not found.", $identifier));
        }

        return $menu;
    }

    public function add(MenuItem $menu): void
    {
        if (isset($this->menus[$menu->getIdentifier()])) {
            throw new \InvalidArgumentException(\sprintf("Menu with identifier '%s' already exists.", $menu->getIdentifier()));
        }

        $this->menus[$menu->getIdentifier()] = $menu;
    }

    public function remove(string $identifier): void
    {
        if ($this->has($identifier)) {
            unset($this->menus[$identifier]);
        }
    }

    public function has(string $identifier): bool
    {
        return isset($this->menus[$identifier]);
    }
}
