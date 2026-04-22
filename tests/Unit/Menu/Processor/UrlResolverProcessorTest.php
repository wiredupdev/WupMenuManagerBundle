<?php

namespace Menu\Processor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\ProcessInterface;
use Wiredupdev\MenuManagerBundle\Menu\Processor\UrlResolverProcessor;

#[CoversClass(UrlResolverProcessor::class)]
class UrlResolverProcessorTest extends TestCase
{
    private ProcessInterface $processor;

    protected function setUp(): void
    {
        $urlGenerator = $this->createStub(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')
            ->willReturn('https://www.example.com/home');

        $request = Request::create('/home', 'GET', [], [], [], ['HTTP_HOST' => 'www.example.com', 'HTTPS' => 'on']);
        $request->attributes->add(['_route' => 'app.home']);
        $requestStack = $this->createMock(RequestStack::class);

        $requestStack->expects($this->atLeastOnce())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->processor = new UrlResolverProcessor($requestStack, $urlGenerator);
    }

    public function testMenuItemWithUrl()
    {
        $item = Item::create('main_menu', 'Home', [
            'url' => 'https://www.example.com/home',
        ]);

        $this->processor->process($item);
        $this->assertSame('https://www.example.com/home', $item->getUrl());
        $this->assertTrue($item->isActive());
    }

    public function testMenuItemWithRoute()
    {
        $item = Item::create('main_menu_home', 'home', [
            'route' => [
                'name' => 'app.home',
            ],
        ]);

        $this->processor->process($item);
        $this->assertSame('https://www.example.com/home', $item->getUrl());
        $this->assertTrue($item->isActive());
    }
}
