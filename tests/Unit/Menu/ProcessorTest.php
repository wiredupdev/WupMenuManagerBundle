<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\ProcessInterface;
use Wiredupdev\MenuManagerBundle\Menu\Processor;

#[CoversClass(Processor::class)]
class ProcessorTest extends TestCase
{
    private Processor $processor;

    protected function setUp(): void
    {
        $this->processor = new Processor();

        $this->processor->addProcess(process: new class implements ProcessInterface {
            public function process(Item $item): void
            {
                $item->addAttribute('html', 'href', $item->getUrl());
                $item->addAttribute('html', 'target', $item->getOption('target'));

                if ($item->isActive()) {
                    $classAttr = \is_array($item->getAttribute('html', 'class'))
                        ? $item->getAttribute('html', 'class')
                        : [$item->getAttribute('html', 'class')];
                    $classAttr[] = 'active';
                    $item->addAttribute('html', 'class', $classAttr);
                }
            }
        });
    }

    public function testProcessing(): void
    {
        $item = Item::create('home', 'Home', ['url' => 'https://example.com/home']);
        $item->activate();
        $item->addAttribute('html', 'class', 'item');

        $this->processor->process($item);

        $this->assertEquals('_self', $item->getAttribute('html', 'target'));
        $this->assertEquals(['item', 'active'], $item->getAttribute('html', 'class'));
        $this->assertEquals('https://example.com/home', $item->getAttribute('html', 'href'));
    }
}
