<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventListener;

use Exception;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HTMLReport;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BehatCoverageEventListener
{
    protected $coverage;

    public function __construct(
        protected ParameterBagInterface $parameters
    ) {
    }

    public function onKernelRequest()
    {
        if ($this->parameters->get('kernel.environment') != 'behat') {
            return;
        }
        $filter = new Filter();
        $filter->includeDirectory($this->parameters->get('kernel.project_dir') . '/vendor/a2global/a2platform/src');

        $this->coverage = new CodeCoverage(
            (new Selector)->forLineCoverage($filter),
            $filter
        );
        $this->coverage->start(uniqid());
    }

    public function onKernelResponse()
    {
        if ($this->parameters->get('kernel.environment') != 'behat') {
            return;
        }

        if (!$this->coverage) {
            throw new Exception('No coverage start');
        }
        $this->coverage->stop();
        $dir = $this->parameters->get('kernel.project_dir') . '/var/Behat/coverage';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($dir . '/' . uniqid() . '.cov', serialize($this->coverage));
    }
}