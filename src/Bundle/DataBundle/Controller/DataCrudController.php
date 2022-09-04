<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Manager\SettingsManager;
use A2Global\A2Platform\Bundle\CoreBundle\Request\ResourceRequest;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Entity\TaggableEntityInterface;
use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/data/", name="admin_data_")
 */
class DataCrudController extends AbstractController
{
    /**
     * @Route("index/{entity}", name="index")
     */
    public function indexAction(Request $request, $entity)
    {
        $datasheet = $this->getIndexDatasheet($entity);

        return $this->render('@Admin/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            DatasheetBuilder::class,
            SettingsManager::class,
        ]);
    }

    protected function getIndexDatasheet($entityClassName): Datasheet
    {
        $datasheet = new Datasheet($this->getDoctrine()->getRepository($entityClassName)->createQueryBuilder('a'));
        $datasheet->setTitle(
            'List of the ' . StringUtility::normalize(StringUtility::getShortClassName($entityClassName))
        );

        $datasheet
            ->getColumn('id')
            ->setType(NumberColumn::class)
            ->setWidth(50);

        return $datasheet;
    }
}