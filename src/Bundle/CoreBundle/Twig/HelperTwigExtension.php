<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HelperTwigExtension extends AbstractExtension
{
    public function __construct(
        protected ParameterBagInterface $parameterBag,
        protected RequestStack          $requestStack,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('parameter', [$this, 'getParameter']),
            new TwigFunction('core_dump_translations', [$this, 'dumpTranslations'], ['is_safe' => ['html']]),
            new TwigFunction('chart_js_data', [$this, 'getChartJsData'], ['is_safe' => ['html']]),
        ];
    }

    public function getParameter($name, $default = null)
    {
        return $this->parameterBag->has($name)
            ? $this->parameterBag->get($name)
            : $default;
    }

    public function dumpTranslations()
    {
        $dump = Yaml::parseFile(sprintf(
            '%s/../Resources/translations/messages.%s.yml',
            __DIR__,
            $this->requestStack->getMainRequest()->getLocale(),
        ));

        return json_encode($dump['ui'], JSON_UNESCAPED_UNICODE);
    }

    public function getChartJsData($data): string
    {
        return json_encode($data);
    }
}