<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber\Datasheet;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnQueryBuilderDatasheetColumnBuildEvent;
use Doctrine\ORM\Query\Expr\Select;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuildQueryBuilderDatasheetColumns implements EventSubscriberInterface
{
    public const SUPPORTED_DATASHEET_TYPE = 'queryBuilder';

    public function __construct(
        protected EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function buildColumns(OnDatasheetBuildEvent $event)
    {
        if (!$event->getDatasheet()->getDatasource() instanceof QueryBuilder) {
            return;
        }
        $columns = [];
        $selectedFieldTypes = $this->getSelectedFieldTypes($event->getDatasheet()->getDatasource());

        foreach ($selectedFieldTypes as $fieldName => $fieldType)  {
            $event = new OnQueryBuilderDatasheetColumnBuildEvent($fieldName, $fieldType, $event->getDatasheet());
            $this->eventDispatcher->dispatch($event);
            $column = $event->getColumn();

//            /** @codeCoverageIgnore */
//            if (!$column) {
//                /** @codeCoverageIgnore */
//                throw new DatasheetBuildException('Failed to build datasheet column: ' . $fieldName . ' type: ' . $fieldType);
//            }
            $columns[] = $column;
        }
        $event->getDatasheet()->setColumns($columns);
    }

    protected function getSelectedFieldTypes(QueryBuilder $queryBuilder)
    {
        $selectParts = $queryBuilder->getDQLPart('select');

        /** Simple queryBuilder with 'select alias' case */
        if (count($selectParts) === 1) {
            /** @var Select $firstPart */
            $firstPart = reset($selectParts);
            $parts = $firstPart->getParts();

            if (count($parts) === 1) {
                $part = reset($parts);

                if ($part === QueryBuilderUtility::getPrimaryAlias($queryBuilder)) {
                    return EntityHelper::getEntityFields(QueryBuilderUtility::getPrimaryClass($queryBuilder));
                }
            }
        }

//        /** @codeCoverageIgnore */
//        throw new DatasheetBuildException('complex QB is not supported yet');
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OnDatasheetBuildEvent::class => ['buildColumns', 600],
        ];
    }
}