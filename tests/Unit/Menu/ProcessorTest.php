<?php

namespace  Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\Processor;
use Wiredupdev\MenuManagerBundle\Menu\Processor\ProcessInterface;

#[CoversClass(Processor::class)]
class ProcessorTest extends TestCase
{
    private Processor $processor;

    protected function setUp(): void
    {
        $this->processor = new Processor();

        $this->processor->addProcess(new class implements ProcessInterface {
            public function process(Item $item): void
            {
                if ('https://www.example.com/' == $item->getUri()) {
                    $item->activate();
                }
            }
        });
    }

    public function testProcessing(): void
    {
        $item = Item::create('home', 'Home', 'https://www.example.com/');

        $this->processor->process($item);

        $this->assertTrue($item->isActive());
    }
}
