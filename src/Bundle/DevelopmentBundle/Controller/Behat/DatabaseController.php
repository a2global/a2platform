<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Controller\Behat;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\DatabaseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("development/behat/database/", name="development_behat_database_")
 */
class DatabaseController extends AbstractController
{
    /**
     * @Route("reset", name="reset")
     */
    public function databaseResetAction()
    {
        $this->checkEnvironment();
        $this->get(DatabaseHelper::class)->clearDB();
        $this->get(DatabaseHelper::class)->migrateMigrations();
        $this->get(DatabaseHelper::class)->importFixture('base');

        return new Response('DB reset successful');
    }

    /**
     * @Route("clear", name="clear")
     */
    public function databaseClearAction()
    {
        $this->checkEnvironment();
        $this->get(DatabaseHelper::class)->clearDB();
        $this->get(DatabaseHelper::class)->migrateMigrations();

        return new Response('DB clear successful');
    }

    /**
     * @codeCoverageIgnore
     */
    protected function checkEnvironment()
    {
        if (!in_array($this->getParameter('kernel.environment'), ['behat', 'dev'])) {
            throw new AccessDeniedException('This controller is for testing purposes only');
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            DatabaseHelper::class,
        ]);
    }
}