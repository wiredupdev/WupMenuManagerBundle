<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\ProcessInterface;
use Wiredupdev\MenuManagerBundle\Menu\Processor;
use Wiredupdev\MenuManagerBundle\Menu\UriGeneratorInterface;

#[CoversClass(Processor::class)]
class ProcessorTest extends TestCase
{
    private Processor $processor;

    private UriGeneratorInterface $uriGenerator;

    protected function setUp(): void
    {
        $this->uriGenerator = $this->createStub(UriGeneratorInterface::class);

        $this->uriGenerator->method('generate')
            ->willReturn('https://example.com/');

        $this->uriGenerator
            ->method('isActive')
            ->willReturn(true);

        $this->uriGenerator
            ->method('getTarget')
            ->willReturn('_self');

        $this->processor = new Processor();

        $this->processor->addProcess(process: new class implements ProcessInterface {
            public function process(Item $item): void
            {
                $item->addAttribute('html', 'href', $item->getUri());
                $item->addAttribute('html', 'target', $item->getUriTarget());

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
        $item = Item::create('home', 'Home', $this->uriGenerator);
        $item->addAttribute('html', 'class', 'item');

        $this->processor->process($item);

        $this->assertEquals('_self', $item->getAttribute('html', 'target'));
        $this->assertEquals(['item', 'active'], $item->getAttribute('html', 'class'));
        $this->assertEquals('https://example.com/', $item->getAttribute('html', 'href'));
    }
}
