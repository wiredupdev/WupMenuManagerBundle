<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Unit\Menu;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Wiredupdev\MenuManagerBundle\Menu\Cacheable;
use Wiredupdev\MenuManagerBundle\Menu\CachedProcessor;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\MenuItemInterface;
use Wiredupdev\MenuManagerBundle\Menu\ProcessInterface;
use Wiredupdev\MenuManagerBundle\Menu\Processor;
use Wiredupdev\MenuManagerBundle\Menu\UriGeneratorInterface;

#[CoversClass(CachedProcessor::class)]
class CachedProcessorTest extends TestCase
{
    private ProcessInterface $processor;

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

        $processor = new Processor([]);

        $processor->addProcess(process: new class implements ProcessInterface, Cacheable {
            public function process(MenuItemInterface $menuItem): void
            {
                $menuItem->addAttribute('html', 'href', $menuItem->getUri());
                $menuItem->addAttribute('html', 'target', $menuItem->getUriTarget());

                if ($menuItem->isActive()) {
                    $classAttr = \is_array($menuItem->getAttribute('html', 'class'))
                        ? $menuItem->getAttribute('html', 'class')
                        : [$menuItem->getAttribute('html', 'class')];
                    $classAttr[] = 'active';
                    $menuItem->addAttribute('html', 'class', $classAttr);
                }
            }
        });

        $cache = new ArrayAdapter();

        $this->processor = new CachedProcessor($processor, $cache);
    }

    public function testCachedProcessing(): void
    {
        $item = Item::create('home', 'Home', $this->uriGenerator);
        $item->addAttribute('html', 'class', 'item');

        $this->processor->process($item);

        $this->assertEquals('_self', $item->getAttribute('html', 'target'));
        $this->assertEquals(['item', 'active'], $item->getAttribute('html', 'class'));
        $this->assertEquals('https://example.com/', $item->getAttribute('html', 'href'));
    }
}
