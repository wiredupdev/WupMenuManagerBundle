<?php

namespace Wiredupdev\MenuManagerBundle;

class MenuItem implements \IteratorAggregate
{
    private array $children = [];

    private array $attributes = [];

    public function __construct(
        private string $identifier,
        private string $label,
        private ?string $uri,
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
        $this->children[$child->getIdentifier()] = $child;
    }

    public function getChild(string $identifier): ?self
    {
        return $this->children[$identifier] ?? null;
    }

    public function hasChildren(): bool
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

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'identifier' => $this->identifier,
            'label' => $this->label,
            'uri' => $this->uri,
            'attributes' => $this->attributes,
            'children' => array_values(array_map(fn (MenuItem $child) => $child->toArray(), $this->children)),
        ];
    }

    public static function fromArray(array $menuItems): self
    {
        $requiredOptions = ['identifier', 'label'];

        if (0 === \count(array_diff_key($requiredOptions, $menuItems))) {
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
}
