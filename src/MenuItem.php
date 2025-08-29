<?php

namespace Wiredupdev\MenuManagerBundle;

class MenuItem implements \IteratorAggregate, \Countable
{
    private array $children = [];

    private array $attributes = [];

    private int $position = 0;

    public function __construct(
        private string $identifier,
        private string $label,
        private ?string $uri,
        private ?self $parent = null,
    ) {
        $this->validateIdentifier($identifier);
    }

    public function addAttribute(string $name, string $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function getAttribute(string $name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

    public function removeAttribute(string $name): self
    {
        if (null !== $this->getAttribute($name)) {
            unset($this->attributes[$name]);
        }

        return $this;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function isRoot(): bool
    {
        return null !== $this->parent;
    }

    public function addChild(self $child): void
    {
        $child->setParent($this);
        $child->setPosition($this->count() + 1);
        $this->children[$child->getIdentifier()] = $child;
    }

    public function getChild(string $identifier): ?self
    {
        return $this->children[$identifier] ?? null;
    }

    public function hasChildren(): bool
    {
        return (bool) $this->count();
    }

    public function removeChild(string $identifier): self
    {
        unset($this->children[$identifier]);

        return $this;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->children);
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->validateIdentifier($identifier);
        $this->identifier = $identifier;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function toArray(): array
    {
        $menuItem = [
            'identifier' => $this->identifier,
            'label' => $this->label,
            'uri' => $this->uri,
            'attributes' => $this->attributes,
            'children' => [],
        ];

        /** @var self $child */
        foreach ($this->children as $child) {
            $menuItem['children'][] = $child->toArray();
        }

        return $menuItem;
    }

    public static function fromArray(array $menuItems): self
    {
        $requiredOptions = ['identifier', 'label'];

        if (\count($requiredOptions) !== \count(array_intersect(array_keys($menuItems), $requiredOptions))) {
            throw new \InvalidArgumentException(\sprintf('The menu should have at least %s options set', implode(', ', $requiredOptions)));
        }

        $menu = new self(
            $menuItems['identifier'],
            $menuItems['label'],
            $menuItems['uri'] ?? null,
            $menuItems['parent'] ?? null
        );

        if (isset($menuItems['attributes'])) {
            foreach ($menuItems['attributes'] as $name => $value) {
                $menu->addAttribute($name, $value);
            }
        }

        if (isset($menuItems['children'])) {
            foreach ($menuItems['children'] as $child) {
                $menu->addChild(self::fromArray($child));
            }
        }

        return $menu;
    }

    private function validateIdentifier(string $identifier): void
    {
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $identifier)) {
            throw new \InvalidArgumentException(\sprintf('Identifier "%s" is invalid, only letters, numbers, underscore and hyphen are allowed ', $identifier));
        }
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function count(): int
    {
        return \count($this->children);
    }

    public function sort($callback, bool $sortNested = false): self
    {
        uasort($this->children, $callback);

        if ($sortNested) {
            /** @var self $child */
            foreach ($this->children as $child) {
                $child->sort($callback);
            }
        }

        return $this;
    }

    public function sortByPosition(bool $sortNested = false): self
    {
        $this->sort(fn (MenuItem $menuA, MenuItem $menuB) => $menuA->getPosition() <=> $menuB->getPosition(), $sortNested);

        return $this;
    }

    public function createItem(string $identifier, string $label, ?string $uri = null): static
    {
        return new self($identifier, $label, $uri);
    }
}
