<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\EventListener\Admin;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\Admin\EntityListDatasheetBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Event\Admin\BuildEntityListEvent;
use A2Global\A2Platform\Bundle\PlatformBundle\Helper\EntityHelper;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Routing\RouterInterface;

#[AsEventListener(event: 'a2platform.admin.entity.list.build', method: 'buildDatasheet', priority: 900)]
class BuildEntityListEventListener
{
    public function __construct(
        protected RouterInterface            $router,
        protected EntityHelper               $entityHelper,
        protected EntityListDatasheetBuilder $entityListDatasheetBuilder,
    ) {
    }

    public function buildDatasheet(BuildEntityListEvent $event): void
    {
        $datasheet = $this->entityListDatasheetBuilder
            ->buildDefaultEntityListDatasheet($event->getEntityClassName());
        $event->setDatasheet($datasheet);
    }
}
