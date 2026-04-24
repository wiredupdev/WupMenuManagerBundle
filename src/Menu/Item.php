<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

class Item implements \IteratorAggregate, \Countable, MenuItemInterface
{
    private array $children = [];

    private array $attributes = [];

    private int $position = 0;

    private bool $visibility = true;

    private bool $active = false;

    private ?self $parent = null;

    public function __construct(
        private string $id,
        private string $label,
        private ?UriGeneratorInterface $uri = null,
    ) {
        $this->validateIdentifier($id);
    }

    public function activate(): self
    {
        $this->active = true;

        return $this;
    }

    public function deactivate(): self
    {
        $this->active = false;

        return $this;
    }

    public function isActive(): bool
    {
        if ($this->uri?->isActive()) {
            $this->activate();
        }

        return $this->active;
    }

    public function show(): self
    {
        $this->visibility = true;

        return $this;
    }

    public function hide(): self
    {
        $this->visibility = false;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visibility;
    }

    public function addAttribute(string $type, string $name, mixed $value): self
    {
        if (!isset($this->attributes[$type])) {
            $this->attributes[$type] = [];
        }
        $this->attributes[$type][$name] = $value;

        return $this;
    }

    public function hasAttribute(string $type, string $name): bool
    {
        return isset($this->attributes[$type][$name]);
    }

    public function removeAttribute(string $type, string $name): self
    {
        if ($this->hasAttribute($type, $name)) {
            unset($this->attributes[$type][$name]);
        }

        return $this;
    }

    public function getAttribute(string $type, string $name): mixed
    {
        return $this->attributes[$type][$name] ?? null;
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
        return null === $this->parent;
    }

    public function addChild(MenuItemInterface $child): self
    {
        $child->setParent($this);
        $child->setPosition($this->count() + 1);
        $this->children[$child->getId()] = $child;

        return $this;
    }

    public function getChild(string $id): ?MenuItemInterface
    {
        return $this->children[$id] ?? null;
    }

    public function hasChildren(): bool
    {
        return (bool) $this->count();
    }

    public function removeChild(string $id): self
    {
        unset($this->children[$id]);

        return $this;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->children);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->validateIdentifier($id);
        $this->id = $id;

        return $this;
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
        return $this->uri?->generate();
    }

    public function getUriTarget(): string
    {
        return $this->uri?->getTarget();
    }

    public function setUri(UriGeneratorInterface $uri): self
    {
        $this->uri = $uri;

        return $this;
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

    public static function create(string $id, string $label, ?UriGeneratorInterface $uri = null): static
    {
        return new self($id, $label, $uri);
    }
}
