<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventListener;

use A2Global\A2Platform\Bundle\DevelopmentBundle\Helper\BehatHelper;
use Exception;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @codeCoverageIgnore
 */
class BehatCoverageEventListener
{
    protected $coverage;

    public function __construct(
        protected ParameterBagInterface $parameters,
        protected BehatHelper $behatHelper,
    ) {
    }

    public function onKernelRequest()
    {
        if ($this->parameters->get('kernel.environment') != 'behat') {
            return;
        }
        $filter = $this->behatHelper->getCodeCoverageFilter();
        $this->coverage = new CodeCoverage((new Selector)->forLineCoverage($filter), $filter);
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