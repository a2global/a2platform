<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu\EntityMenuBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Menu\MenuBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Component\Menu\Menu;
use A2Global\A2Platform\Bundle\PlatformBundle\Registry\TwigBlockRegistry;
use A2Global\A2Platform\Bundle\PlatformBundle\Twig\Block\TwigBlockInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PlatformTwigExtension extends AbstractExtension
{
    public function __construct(
        protected MenuBuilder       $menuBuilder,
        protected EntityMenuBuilder $entityMenuBuilder,
        protected RequestStack      $requestStack,
        protected TwigBlockRegistry $twigBlockRegistry,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('menu', [$this, 'getMenu'], ['is_safe' => ['html']]),
            new TwigFunction('singleEntityMenu', [$this, 'getSingleEntityMenu'], ['is_safe' => ['html']]),
            new TwigFunction('hasTwigBlocks', [$this, 'hasTwigBlocks']),
            new TwigFunction('twigBlocks', [$this, 'twigBlocks']),
        ];
    }

    public function hasTwigBlocks(string $containerName): bool
    {
        return
            count($this->twigBlockRegistry->findSupporting($containerName, $this->requestStack->getMainRequest())) > 0;
    }

    public function twigBlocks(string $containerName, string $separator = '<div class="my-2">&nbsp;</div>'): string
    {
        $contentBlocks = array_map(function (TwigBlockInterface $block) {
            return $block->getContent();
        }, $this->twigBlockRegistry->findSupporting($containerName, $this->requestStack->getMainRequest()));

        return implode($separator, $contentBlocks);
    }

    public function getMenu(string $name): Menu
    {
        return $this->menuBuilder->build($name);
    }

    public function getSingleEntityMenu(object $object): Menu
    {
        return $this->entityMenuBuilder->getSingleEntityMenu($object);
    }
}