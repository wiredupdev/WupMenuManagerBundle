<?php

namespace Wiredupdev\MenuManagerBundle\Tests\Integration\Twig;

use PHPUnit\Framework\Attributes\CoversClass;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Wiredupdev\MenuManagerBundle\Twig\MenuManagerExtension;

#[CoversClass(MenuManagerExtension::class)]
class WudMenuManagerTest extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    protected function setUp(): void
    {
        static::bootKernel([
            'debug' => false,
            'environment' => 'test',
        ]);
    }

    public function testMenuRender()
    {
        $container = static::getContainer();

        /** @var Environment $twig */
        $twig = $container->get(Environment::class);

        $loader = new ChainLoader([
            new ArrayLoader([
                'header.html.twig' => '{{ menu("main_menu_site") }}',
            ]),
            $twig->getLoader(),
        ]);

        $twig->setLoader($loader);

        $rendered = $twig->render('header.html.twig');

        $this->assertStringContainsString('id="main_menu_site"', $rendered);
    }
}
