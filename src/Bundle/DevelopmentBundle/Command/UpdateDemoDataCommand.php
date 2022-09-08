<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Address;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
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

        foreach ($this->entityManager->getRepository(Person::class)->findAll() as $person) {
            $address = $this->entityManager->getRepository(Address::class)->find(rand(1, 50));
            $version = rand(0, 15) . '.' . rand(0,12);
            $person
                ->setAddress($address)
                ->setAge(rand(20,50))
                ->setVersion((float)$version);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}
