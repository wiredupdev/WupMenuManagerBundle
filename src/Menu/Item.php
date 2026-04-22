<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

class Item implements \IteratorAggregate, \Countable
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
        private array $options = [],
    ) {
        $this->validateIdentifier($id);

        $this->options = array_merge([
            'url' => null,
            'route' => [
                'name' => null,
                'parameters' => [],
            ],
            'target' => '_self',
        ], $this->options);
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

    public function addAttribute(string $ype, string $name, mixed $value): self
    {
        if (!isset($this->attributes[$ype])) {
            $this->attributes[$ype] = [];
        }
        $this->attributes[$ype][$name] = $value;

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

    public function addChild(self $child): self
    {
        $child->setParent($this);
        $child->setPosition($this->count() + 1);
        $this->children[$child->getId()] = $child;

        return $this;
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

    public function setUrl(?string $url): self
    {
        $this->options['url'] = $url;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->options['url'] ?? null;
    }

    public function getOption(string $name, mixed $default = null): mixed
    {
        return $this->options[$name] ?? $default;
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

    public static function create(string $id, string $label, array $options = []): static
    {
        return new self($id, $label, $options);
    }
}
