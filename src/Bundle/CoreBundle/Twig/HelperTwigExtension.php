<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Twig;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use DateTimeInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class HelperTwigExtension extends AbstractExtension
{
    public function __construct(
        protected ParameterBagInterface $parameterBag
    ) {

    }

    public function getFunctions()
    {
        return [
            new TwigFunction('parameter', [$this, 'getParameter']),
        ];
    }

    public function getParameter($name, $default = null)
    {
        return $this->parameterBag->has($name)
            ? $this->parameterBag->get($name)
            : $default;
    }
}