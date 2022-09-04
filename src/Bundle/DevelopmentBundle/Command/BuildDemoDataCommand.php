<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'development:demo-data:build',
    description: 'Modify demo data',
)]
class BuildDemoDataCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();

        return Command::SUCCESS;
    }
}
