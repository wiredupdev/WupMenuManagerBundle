<?php

namespace Wiredupdev\MenuManagerBundle\Twig;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Wiredupdev\MenuManagerBundle\Menu\Manager;
use Wiredupdev\MenuManagerBundle\Menu\Processor;

class MenuManagerExtension extends AbstractExtension
{
    public function __construct(
        private Manager $menuManager,
        private Processor $processor,
        private Environment $twig,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('menu', [$this, 'renderMenu'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function renderMenu(string $id, string $template = '@WudMenuManager/default.html.twig'): string
    {
        return $this->twig->render($template, ['menu' => $this->menuManager->get($id), 'processor' => $this->processor]);
    }
}
