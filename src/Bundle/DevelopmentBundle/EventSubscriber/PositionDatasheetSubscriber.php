<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\Datasheet;
use A2Global\A2Platform\Bundle\DataBundle\Event\OnEntityListDatasheetBuild;
use A2Global\A2Platform\Bundle\DataBundle\Provider\DatasheetProvider;
use A2Global\A2Platform\Bundle\DevelopmentBundle\Entity\Position;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PositionDatasheetSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected DatasheetProvider      $datasheetProvider,
    ) {
    }

    public function buildDatasheet(OnEntityListDatasheetBuild $event)
    {
        if ($event->getEntityClassName() != Position::class) {
            return;
        }
        $datasheet = $this->datasheetProvider->getDefaultEntityListDatasheet(Position::class, false);

        $datasheet->getColumn('id')
            ->setWidth(100);

        $datasheet->getColumn('caption')
            ->setLink(['admin_data_view', ['entity' => Position::class]])
            ->setBold(true);

        $datasheet->hideColumns('columnThatDoesntExists4630947564536');

        $event->setDatasheet($datasheet);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnEntityListDatasheetBuild::class => 'buildDatasheet',
        ];
    }
}