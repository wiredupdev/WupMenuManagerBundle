<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

use Wiredupdev\MenuManagerBundle\Menu\Item\AttributeType;

class Item implements \IteratorAggregate, \Countable
{
    private array $children = [];
    private array $attributes = [];
    private int $position = 0;
    private bool $visibility = true;
    private bool $active = false;
    private array $roles = [];
    private ?self $parent = null;

    public function __construct(
        private string $id,
        private string $label,
        private ?string $uri = null
    )
    {
        $this->setId($id);
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

    public function addAttribute(AttributeType $attributeType, string $name, mixed $value): self
    {
        $this->attributes[$attributeType->value][$name] = $value;

        return $this;
    }

    public function hasAttribute(AttributeType $attributeType, string $name): bool
    {
        return isset($this->attributes[$attributeType->value][$name]);
    }

    public function removeAttribute(AttributeType $attributeType, string $name): self
    {
        if ($this->hasAttribute($attributeType, $name)) {
            unset($this->attributes[$attributeType->value][$name]);
        }

        return $this;
    }

    public function getAttribute(AttributeType $attributeType, string $name): mixed
    {
        return $this->attributes[$attributeType->value][$name] ?? null;
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
        return (bool)$this->count();
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

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function removeRole(string $role): self
    {
        if($roleKey = array_search($role, $this->roles, true)){
            unset($this->roles[$roleKey]);
        }
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function toArray(): array
    {
        $menuItem = [
            'id' => $this->id,
            'label' => $this->label,
            'uri' => $this->uri,
            'attributes' => $this->attributes,
            'active' => $this->active,
            'visibility' => $this->visibility,
            'roles' => $this->roles,
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
        $requiredOptions = ['id', 'label'];

        if (\count($requiredOptions) !== \count(array_intersect(array_keys($menuItems), $requiredOptions))) {
            throw new \InvalidArgumentException(\sprintf('The menu should have at least %s options set', implode(', ', $requiredOptions)));
        }

        $menu = new self(
            $menuItems['id'],
            $menuItems['label'],
            $menuItems['uri'] ?? null,
        );

        if (isset($menuItems['attributes'])) {
            foreach ($menuItems['attributes'] as $type => $attribute) {
                foreach ($attribute as $name => $value) {
                    $menu->addAttribute(AttributeType::from($type), $name, $value);
                }
            }
        }

        if (isset($menuItems['children'])) {
            foreach ($menuItems['children'] as $child) {
                $menu->addChild(self::fromArray($child));
            }
        }

        if (isset($menuItems['visibility']) && (false === $menuItems['visibility'])) {
            $menu->hide();
        }

        if (isset($menuItems['active_page']) && (true === $menuItems['active_page'])) {
            $menu->activate();
        }

        if (isset($menuItems['roles']) && \is_array($menuItems['roles'])) {
            $menu->setRoles($menuItems['roles']);
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
        $this->sort(fn(Item $menuA, Item $menuB) => $menuA->getPosition() <=> $menuB->getPosition(), $sortNested);

        return $this;
    }

    public static function create(string $identifier, string $label, ?string $uri = null): static
    {
        return new self($identifier, $label, $uri);
    }
}
