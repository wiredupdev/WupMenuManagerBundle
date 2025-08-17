<?php

namespace Wiredupdev\MenuManagerBundle;

use Traversable;

class MenuItem implements \IteratorAggregate
{

    private array $children = [];


    private array $staticAttributes = [
        '_identifier'
    ];

    public function __construct(
        string $identifier,
        public string     $label,
        public string     $target,
        private array     $attributes = [],
        private ?MenuItem $parent = null,
    )
    {
        $this->addAttribute('_identifier', $identifier);
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
        if(in_array($name, $this->staticAttributes)) {
           return;
        }

        unset($this->attributes[$name]);
    }

    public function setParent(?MenuItem $parent): void
    {
        $this->parent = $parent;
    }

    public function addChild(MenuItem $child): void
    {
        $child->setParent($this);
        $this->children[] = $child;
    }

    public function getChild(int $index): ?MenuItem
    {
        return $this->children[$index] ?? null;
    }

    public function removeChild(MenuItem $child): void
    {
        $key = array_search($child, $this->children);

       unset($this->children[$key]);
    }

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'target' => $this->target,
            'attributes' => $this->attributes,
            'parent' => $this->parent,
            'children' => array_map(fn (MenuItem $child) => $child->toArray(), $this->children),
        ];
    }
}