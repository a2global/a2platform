<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\DataReader;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Event\DataReader\OnQueryBuilderFieldsBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Exception\DatasheetBuildException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuildFieldsFromQueryBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnQueryBuilderFieldsBuildEvent::class => ['buildFields', 200],
        ];
    }

    public function buildFields(OnQueryBuilderFieldsBuildEvent $event)
    {
        $queryBuilder = $event->getQueryBuilder();
        $selects = $queryBuilder->getDQLPart('select');

        if (count($selects) !== 1) {
            throw new DatasheetBuildException('Not supports custom dql for now');
        }
        $entityFields = EntityHelper::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder));
        $event->setFields(array_keys($entityFields));
    }
}
