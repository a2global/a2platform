<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Helper;

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DatabaseHelper
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected Kernel                 $kernel,
        protected ParameterBagInterface  $parameters,
    ) {
    }

    public function clearDB()
    {
        $tables = $this->entityManager->getConnection()->fetchAllAssociative('SHOW TABLES;');
        $this->entityManager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $table) {
            $this->entityManager->getConnection()->executeQuery('DROP TABLE ' . reset($table));
        }
        $this->entityManager->getConnection()->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function migrateMigrations()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $application->run(new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction',
        ]), new BufferedOutput());
    }

    public function importFixture($name)
    {
        $filepath = sprintf(
            '%s/tests/Behat/fixtures/%s.sql',
            $this->parameters->get('kernel.project_dir'),
            $name
        );

        if(filesize($filepath) < 1){
            return;
        }
        $this->entityManager->getConnection()->executeQuery(file_get_contents($filepath));
    }
}