<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HTMLReport;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'development:behat:build-coverage-report',
    description: 'Merge coverage results and build HTML report',
)]
class BuildCoverageReportCommand extends Command
{
    public function __construct(
        protected ParameterBagInterface $parameterBag,
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $behatDir = $this->parameterBag->get('kernel.project_dir') . '/var/Behat';
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

        (new HTMLReport)->process($totalCoverage, $behatDir . '/report');

        return Command::SUCCESS;
    }
}
