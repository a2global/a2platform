<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\DataReader;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Event\DataReader\OnQueryBuilderFieldsBuildEvent;
use A2Global\A2Platform\Bundle\DatasheetBundle\Exception\DatasheetBuildException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuildFieldsFromQueryBuilderSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            OnQueryBuilderFieldsBuildEvent::class => ['buildFields', 200],
        ];
    }

    public function buildFields(OnQueryBuilderFieldsBuildEvent $event)
    {
        $queryBuilder = $event->getQueryBuilderDataReader()->getSource();
        $selects = $queryBuilder->getDQLPart('select');

        if (count($selects) !== 1) {
            throw new DatasheetBuildException('Not supports custom dql for now');
        }
        $fields = array_map(function ($field) {
            return $field['name'];
        }, QueryBuilderUtility::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder)));
        $event->getQueryBuilderDataReader()->setFields($fields);
    }
}