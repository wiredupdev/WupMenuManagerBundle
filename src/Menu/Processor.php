<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

class Processor implements ProcessorInterface
{
    public function __construct(
        private array $processes = [],
    ) {
        foreach ($processes as $process) {
            $this->addProcess($process);
        }
    }

    public function addProcess(ProcessInterface $process): void
    {
        $this->processes[$process::class] = $process;
    }

    public function hasProcess(string $class): bool
    {
        return isset($this->processes[$class]);
    }

    public function removeProcess(string $class): void
    {
        if ($this->hasProcess($class)) {
            unset($this->processes[$class]);
        }
    }

    public function process(MenuItemInterface $menuItem): void
    {
        foreach ($this as $process) {
            $process->process($menuItem);
        }
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->processes);
    }
}
