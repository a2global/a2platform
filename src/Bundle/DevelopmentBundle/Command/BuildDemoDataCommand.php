<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Address;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Company;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'development:demo-data:build',
    description: 'Build/modify demo data',
)]
class BuildDemoDataCommand extends Command
{
    const DEMO_DATA_FILEPATH = __DIR__ . '/../Resources/demo_data/data.json';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();
        $addresses = [];
        $companies = [];
        $persons = [];

        for ($i = 0; $i < 50; $i++) {
            $addresses[] = [
                'country' => $faker->country,
                'city' => $faker->city,
                'street' => $faker->streetName,
                'address' => $faker->streetAddress,
            ];
        }

        for ($i = 0; $i < 10; $i++) {
            $companies[] = [
                'name' => $faker->company,
                'address' => rand(1,50),
            ];
        }

        for ($i = 0; $i < 300; $i++) {
            $persons[] = [
                'firstName' => $faker->firstName,
                'lastName' => $faker->lastName,
                'address' => rand(1,50),
                'phoneNumber' => $faker->phoneNumber,
                'description' => $faker->email,
                'active' => $faker->boolean(),
                'birthdate' => $faker->date('Y-m-d'),
                'lastActiveAt' => $faker->date('Y-m-d') . ' ' . $faker->time('h:i:s'),
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'company' => rand(1,10),
            ];
        }

        $data = [
            'addresses' => $addresses,
            'companies' => $companies,
            'persons' => $persons,
        ];

//        file_put_contents(self::DEMO_DATA_FILEPATH, json_encode($data));

        return Command::SUCCESS;
    }
}
