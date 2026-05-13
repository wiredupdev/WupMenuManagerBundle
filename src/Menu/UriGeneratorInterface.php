<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

interface UriGeneratorInterface
{
    public function generate();

    public function isActive(): bool;

    public function getParams(): array;

    public function getTarget(): string;

    public function getType(): string;

    public function getRawUri(): string;
}
