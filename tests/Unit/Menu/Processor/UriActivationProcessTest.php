<?php

namespace Unit\Menu\Processor;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\Processor\UriActivationProcess;

#[CoversClass(UriActivationProcess::class)]
class UriActivationProcessTest extends TestCase
{
    private UriActivationProcess $process;

    protected function setUp(): void
    {
        $requestStack = $this->createMock(RequestStack::class);
        $requestStack->method('getCurrentRequest')->willReturn(Request::create('/test-path'));
        $this->process = new UriActivationProcess($requestStack);
    }

    public function testUriActivationProcess(): void
    {
        $item = Item::create('test_activation', 'Test Activation', 'http://localhost/test-path');
        $this->process->process($item);

        $this->assertTrue($item->isActive());
    }
}
