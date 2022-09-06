<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Helper;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Filter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HTMLReport;


class BehatHelper
{
    public function __construct(
        protected ParameterBagInterface $parameters,
    ) {
    }

    public function getCodeCoverageFilter(): Filter
    {
        $a2platformRootDirectory = $this->parameters->get('kernel.project_dir') . '/vendor/a2global/a2platform/src/Bundle';
        $filter = new Filter();

        foreach (glob($a2platformRootDirectory . '/*') as $bundleDirectory) {
            if (basename($bundleDirectory) === 'DevelopmentBundle') {
                continue;
            }
            $filter->includeDirectory($bundleDirectory);
            $filter->excludeDirectory($bundleDirectory . '/DependencyInjection');
        }

        return $filter;
    }

    public function generateCoverageReport()
    {
        $behatDir = $this->parameters->get('kernel.project_dir') . '/var/Behat';
        $totalCoverage = null;

        foreach (glob($behatDir . '/coverage/*.cov') as $coverageResultFile) {
            /** @var CodeCoverage $coverage */
            $coverage = unserialize(file_get_contents($coverageResultFile));

            if ($totalCoverage) {
                $totalCoverage->merge($coverage);
            } else {
                $totalCoverage = $coverage;
            }
        }
        $targetPath = $this->parameters->get('kernel.project_dir') . '/public/etc/behat-coverage-report';

        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        (new HTMLReport)->process($totalCoverage, $targetPath);
    }
}