<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Command;

use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Address;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Company;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'development:demo-data:import',
    description: 'Import demo data',
)]
class ImportDemoDataCommand extends Command
{
    const DEMO_DATA_FILEPATH = __DIR__ . '/../Resources/demo_data/data.json';

    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = json_decode(file_get_contents(BuildDemoDataCommand::DEMO_DATA_FILEPATH), true);

        foreach ($data['addresses'] as $address) {
            $address = $this->fromArray(new Address(), $address);
            $this->entityManager->persist($address);
        }
        $this->entityManager->flush();

        foreach ($data['companies'] as $company) {
            $company['address'] = $this->entityManager->getRepository(Address::class)->find($company['address']);
            $company = $this->fromArray(new Company(), $company);
            $this->entityManager->persist($company);
        }
        $this->entityManager->flush();

        foreach ($data['persons'] as $person) {
            $person['address'] = $this->entityManager->getRepository(Address::class)->find($person['address']);
            $person['company'] = $this->entityManager->getRepository(Company::class)->find($person['company']);
            $person['birthdate'] = new DateTime($person['birthdate']);
            $person['lastActiveAt'] = new DateTime($person['lastActiveAt']);
            $person['company'] = $this->entityManager->getRepository(Company::class)->find($person['company']);
            $person = $this->fromArray(new Person(), $person);
            $this->entityManager->persist($person);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    protected function fromArray($object, $data)
    {
        foreach($data as $key=>$value){
            $setter = 'set' . $key;
            $object->$setter($value);
        }

        return $object;
    }
}
