<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Address;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'development:demo-data:update',
    description: 'Modify demo data',
)]
class UpdateDemoDataCommand extends Command
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();
        $positions = $this->entityManager->getRepository(Position::class)->findAll();

        foreach ($this->entityManager->getRepository(Person::class)->findAll() as $person) {
            $person->setPosition($faker->randomElement($positions));
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
