<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\DataReader;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Event\DataReader\OnQueryBuilderFieldsBuildEvent;
use Doctrine\ORM\Query\Expr\Select;
use Doctrine\ORM\QueryBuilder;
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
        $fields = [];
        $queryBuilder = $event->getQueryBuilderDataReader()->getSource();

        /** @var Select $select */
        foreach ($queryBuilder->getDQLPart('select') as $select) {
            $fieldPath = $select->getParts()[0];
            $pathParts = explode('.', $fieldPath);
            $alias = $pathParts[0];
            $fieldName = $pathParts[1];
            $fields[] = $fieldName;
        }
        $event->getQueryBuilderDataReader()->setFields($fields);
    }
}