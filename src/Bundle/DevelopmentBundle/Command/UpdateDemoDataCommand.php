<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Address;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Company;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Position;
use DateTime;
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

        for ($i = 0; $i < 10; $i++) {
            $person = (new Person)
                ->setFullname($faker->firstName . ' ' . $faker->lastName)
                ->setAddress($this->entityManager->getRepository(Address::class)->find(rand(1, 10)))
                ->setPosition($this->entityManager->getRepository(Position::class)->find(rand(1, 10)))
                ->setCompany($this->entityManager->getRepository(Company::class)->find(rand(1, 10)))
                ->setAge(rand(18, 50))
                ->setVersion(rand(0, 5) . '.' . rand(0, 9))
                ->setEmail($faker->email)
                ->setPhonenumber($faker->phoneNumber)
                ->setIsActive($faker->boolean())
                ->setBirthdate(new DateTime($faker->date('y-m-d')))
                ->setLastActiveAt(new DateTime($faker->date('y-m-d h:i:s')))
                ->setLatitude(rand(0,99).'.'.rand(100000,999999));
            $this->entityManager->persist($person);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
