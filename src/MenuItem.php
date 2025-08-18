<?php

namespace Wiredupdev\MenuManagerBundle;

class MenuItem implements \IteratorAggregate
{
    private array $children = [];

    public function __construct(
        public string|int $identifier,
        public string $label,
        public string $target,
        private array $attributes = [],
        private ?self $parent = null,
    ) {
    }

    public function addAttribute(string $name, string $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

    public function removeAttribute(string $name): void
    {
        if (null === $this->getAttribute($name)) {
            return;
        }

        unset($this->attributes[$name]);
    }

    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
    }

    public function addChild(self $child): void
    {
        $child->setParent($this);
        $this->children[] = $child;
    }

    public function getChild(int $index): ?self
    {
        return $this->children[$index] ?? null;
    }

    public function hasChild(): bool
    {
        return (bool) \count($this->children);
    }

    public function removeChild(self $child): void
    {
        $key = array_search($child, $this->children);

        unset($this->children[$key]);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier,
            'label' => $this->label,
            'target' => $this->target,
            'attributes' => $this->attributes,
            'parent' => $this->parent,
            'children' => array_map(fn (MenuItem $child) => $child->toArray(), $this->children),
        ];
    }
}
