<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\DataReader;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Event\DataReader\OnQueryBuilderFieldsBuildEvent;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExposeQueryBuilderSelectSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnQueryBuilderFieldsBuildEvent::class => ['exposeSelectPart', 300],
        ];
    }

    public function exposeSelectPart(OnQueryBuilderFieldsBuildEvent $event)
    {
        $queryBuilder = $event->getQueryBuilderDataReader()->getSource();
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