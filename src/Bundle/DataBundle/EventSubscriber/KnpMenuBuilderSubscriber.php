<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected EntityHelper $entityHelper,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 100],
        ];
    }

    public function onSetupMenu(KnpMenuEvent $event)
    {
        $menu = $event->getMenu();
        $this->addEntityCrudItems($menu);
    }

    protected function addEntityCrudItems(ItemInterface $menu)
    {
        $entities = $this->entityHelper->getEntityList();

        $menu->addChild('entities', [
            'label' => 'DATA',
        ])->setAttribute('class', 'header');

        $appEntities = array_filter($entities, function ($className) {
            return str_starts_with($className, 'App\Entity');
        });

        foreach ($appEntities as $entity) {
            $this->addEntityCrudItem($menu, 'entities.app', $entity);
        }

        $bundleEntities = array_filter($entities, function ($className) {
            return !str_starts_with($className, 'App\Entity');
        });

        $currentBundleName = null;

        foreach ($bundleEntities as $entity) {
            $bundleName = StringUtility::getBundleNameFromClass($entity, 'Bundle');

            if ($currentBundleName != $bundleName) {
                $bundleMenuName = 'entities.bundle.' . StringUtility::toCamelCase($bundleName);
                $menu->addChild($bundleMenuName, [
                    'label' => $bundleName,
                    'attributes' => [
                        'data-admin-menu-item' => $bundleMenuName,
                    ],
                ]);
                $subMenu = $menu->getChild($bundleMenuName);
                $currentBundleName = $bundleName;
            }
            $this->addEntityCrudItem($subMenu, $bundleMenuName, $entity);
        }
    }

    protected function addEntityCrudItem($menu, $name, $entity)
    {
        $entityName = StringUtility::getShortClassName($entity);
        $menuName = $name . '.' . StringUtility::toCamelCase($entityName);
        $menu->addChild($menuName, [
            'label' => StringUtility::normalize($entityName),
            'route' => 'admin_data_index',
            'routeParameters' => [
                'entity' => $entity,
            ],
            'attributes' => [
                'data-admin-menu-item' => $menuName,
            ],
        ]);
    }
}