<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\MenuBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PlatformTwigExtension extends AbstractExtension
{
    public function __construct(
        protected MenuBuilder $menuBuilder,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('menu', [$this, 'getMenu'], ['is_safe' => ['html']]),
        ];
    }

    public function getMenu(string $name): Menu
    {
        return $this->menuBuilder->build($name);
    }
}