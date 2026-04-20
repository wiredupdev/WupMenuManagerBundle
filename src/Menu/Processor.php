<?php

namespace Wiredupdev\MenuManagerBundle\Menu;

class Processor implements ProcessInterface
{
    private array $processes = [];

    public function __construct(
        array $processes = [],
    ) {
        foreach ($processes as $process) {
            $this->addProcess($process);
        }
    }

    public function addProcess(ProcessInterface $process): void
    {
        $this->processes[$process::class] = $process;
    }

    public function removeProcess(string $class): void
    {
        unset($this->processes[$class]);
    }

    public function process(Item $item): void
    {
        foreach ($this->processes as $process) {
            $process->process($item);
        }
    }
}
