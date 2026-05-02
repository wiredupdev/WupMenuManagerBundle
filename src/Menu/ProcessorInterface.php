<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

interface ProcessorInterface extends ProcessInterface, \IteratorAggregate
{

    public function addProcess(ProcessInterface $process): void;

    public function removeProcess(string $class): void;

    public function hasProcess(string $class): bool;
}
