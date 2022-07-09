<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DatasheetBundle\Event\OnDataBuildEvent;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExposeQueryBuilderSelectSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnDataBuildEvent::class => ['exposeQueryBuilderSelect', 600],
        ];
    }

    public function exposeQueryBuilderSelect(OnDataBuildEvent $event)
    {
        $queryBuilder = $event->getDatasheet()->getConfig()['dataSource'];

        if (!$queryBuilder instanceof QueryBuilder) {
            return;
        }
        $selects = $queryBuilder->getDQLPart('select');

        if (count($selects) !== 1) {
            return;
        }
        $select = reset($selects);
        $selectParts = $select->getParts();
        $selectPart = reset($selectParts);
        $primaryAlias = QueryBuilderUtility::getPrimaryAlias($queryBuilder);

        if ($selectPart !== $primaryAlias) {
            return;
        }
        $queryBuilder->resetDQLPart('select');
        $fields = QueryBuilderUtility::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder));

        foreach ($fields as $field) {
            $queryBuilder->addSelect(sprintf('%s.%s', $primaryAlias, $field['name']));
        }
    }
}
