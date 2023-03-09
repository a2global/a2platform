<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig\Block;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;

#[AutoconfigureTag('a2platform.twig.block')]
interface TwigBlockInterface
{
    public function supports(string $containerName, Request $request): bool;

    public function getContent(): string;
}