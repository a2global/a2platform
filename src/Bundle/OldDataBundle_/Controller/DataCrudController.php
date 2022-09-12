<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Manager\SettingsManager;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use Doctrine\ORM\AbstractQuery;
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

    /**
     * @Route("view/{entity}/{id}", name="view")
     */
    public function viewAction(Request $request, $entity, $id)
    {
        $data = $this->getDoctrine()
            ->getRepository($entity)
            ->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        dd($data);
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
        $fields = QueryBuilderUtility::getEntityFields($entityClassName);

        $datasheet
            ->getColumn('id')
            ->setType(NumberColumn::class)
            ->setWidth(50);

        $datasheet
            ->getColumn($fields[1]['name'])
            ->setActionRouteName('admin_data_view')
            ->setActionRouteParams([
                'entity' => $entityClassName,
            ]);

        return $datasheet;
    }
}