<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

class Manager
{
    private array $menus = [];

    public function __construct(iterable $configs = [])
    {
        $this->configure($configs);
    }

    public function configure(iterable $configs = []): void
    {
        if (isset($configs['menu_classes'])) {
            foreach ($configs['menu_classes'] as $class) {
                if (false === method_exists($class, '__invoke')) {
                    throw new Exception(\sprintf('Class "%s" must implement __invoke method.', $class::class));
                }
                $class($this);
            }
        }
    }

    public function get(string $identifier): Item
    {
        if (($menu = $this->menus[$identifier]) === null) {
            throw new \InvalidArgumentException(\sprintf("Menu with identifier '%s' not found.", $identifier));
        }

        return $menu;
    }

    public function add(Item $menu): void
    {
        if (isset($this->menus[$menu->getId()])) {
            throw new \InvalidArgumentException(\sprintf("Menu with identifier '%s' already exists.", $menu->getId()));
        }

        $this->menus[$menu->getId()] = $menu;
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
