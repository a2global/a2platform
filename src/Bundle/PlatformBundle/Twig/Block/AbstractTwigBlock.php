<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig\Block;

use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

abstract class AbstractTwigBlock implements TwigBlockInterface
{
    protected Environment $twig;

    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /** @required */
    public function setTwig(Environment $twig): self
    {
        $this->twig = $twig;
        return $this;
    }

    public function getRouteName(Request $request): string
    {
        return $request->attributes['_route'];
    }
}