<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use A2Global\A2Platform\Bundle\DevelopmentBundle\Helper\BehatHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'development:behat:build-coverage-report',
    description: 'Merge coverage results and build HTML report',
)]
class BuildCoverageReportCommand extends Command
{
    public function __construct(
        protected BehatHelper $behatHelper,
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->behatHelper->getCodeCoverageFilter();

        return Command::SUCCESS;
    }
}
