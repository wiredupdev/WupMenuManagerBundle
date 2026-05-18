<?php

namespace Wiredupdev\MenuManagerBundle\Menu\Process;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Wiredupdev\MenuManagerBundle\Menu\MenuItemInterface;
use Wiredupdev\MenuManagerBundle\Menu\ProcessInterface;

readonly class SecurityProcess implements ProcessInterface
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function process(MenuItemInterface $menuItem): void
    {

        if (($roles = $menuItem->getAttribute('security', 'roles'))
            && [] !== $roles && false === $this->authorizationChecker->isGranted($roles)) {
            $menuItem->hide();
        }

        if ($menuItem->hasChildren()) {
            foreach ($menuItem as $child) {
                $this->process($child);
            }
        }
    }
}
