<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Controller\Behat;

use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use A2Global\A2Platform\Bundle\DataBundle\Exception\DatasheetBuildException;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("development/behat/datasheet/", name="development_behat_datasheet_")
 */
class DatasheetController extends AbstractController
{
    /**
     * @Route("array", name="array")
     */
    public function arrayDatasheetAction()
    {
        $source = $this->getDoctrine()
            ->getRepository(Person::class)
            ->createQueryBuilder('p')
            ->getQuery()
            ->getArrayResult();

        $datasheet = new Datasheet($source, 'List of the person (from array)');

        return $this->render('@Admin/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    /**
     * @Route("querybuilder/simple", name="querybuilder_simple")
     */
    public function simpleQueryBuilderDatasheetAction()
    {
        $source = $this->getDoctrine()
            ->getRepository(Person::class)
            ->createQueryBuilder('p');

        $datasheet = new Datasheet($source, 'List of the person (from simple query builder)');

        return $this->render('@Admin/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    /**
     * @Route("querybuilder/complex", name="querybuilder_complex")
     */
    public function complexQueryBuilderDatasheetAction()
    {
        $source = $this->getDoctrine()
            ->getRepository(Person::class)
            ->createQueryBuilder('p')
            ->select('p.fullname')
            ->addSelect('p.email');

        $datasheet = new Datasheet($source, 'List of the person (from complex query builder)');

        return $this->render('@Admin/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    /**
     * @Route("errors", name="errors")
     */
    public function datasheetErrorsAction()
    {
        $datasheets = [
            new Datasheet(new DatasheetBuildException()),
        ];

        return $this->render('@Development/behat/datasheet/errors.html.twig', [
            'datasheets' => $datasheets,
        ]);
    }
}