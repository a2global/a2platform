<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
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
            new TwigFunction('chart_js_data', [$this, 'getChartJsData'], ['is_safe' => ['html']]),
        ];
    }

    public function getParameter($name, $default = null)
    {
        return $this->parameterBag->has($name)
            ? $this->parameterBag->get($name)
            : $default;
    }

    public function getChartJsData($data): string
    {
        return json_encode($data);
    }
}