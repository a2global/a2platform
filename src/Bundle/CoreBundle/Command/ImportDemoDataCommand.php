<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Command;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\DatabaseHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'core:demo-data:import',
    description: 'Import demo data',
)]
class ImportDemoDataCommand extends Command
{
    public function __construct(
        protected DatabaseHelper $databaseHelper,
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->databaseHelper->clearDB();
        $this->databaseHelper->migrateMigrations();
        $this->databaseHelper->importFixture('base');

        return Command::SUCCESS;
    }
}
