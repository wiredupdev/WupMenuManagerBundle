<?php

namespace Wiredupdev\MenuManagerBundle;

class MenuCollection implements \IteratorAggregate, \Countable
{
    private array $menus = [];

    public function add(MenuItem $menu): void
    {
        if ($this->has($menu->getIdentifier())) {
            throw new \InvalidArgumentException(\sprintf("Menu with identifier '%s' already exists.", $menu->getIdentifier()));
        }

        $this->menus[$menu->getIdentifier()] = $menu;
    }

    public function has($menuIdentifier): bool
    {
        return isset($this->menus[$menuIdentifier]);
    }

    public function get($menuIdentifier): ?MenuItem
    {
        return $this->menus[$menuIdentifier] ?? null;
    }

    public function remove($menuIdentifier): void
    {
        if ($this->has($menuIdentifier)) {
            unset($this->menus[$menuIdentifier]);
        }
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->menus);
    }

    public function count(): int
    {
        return \count($this->menus);
    }
}
