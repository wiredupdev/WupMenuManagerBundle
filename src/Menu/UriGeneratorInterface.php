<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

interface UriGeneratorInterface
{
    public function generate();

    public function isActive(): bool;

    public function getParams(): array;

    public function getTarget(): string;
}
