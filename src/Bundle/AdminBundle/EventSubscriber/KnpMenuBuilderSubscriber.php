<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\EventSubscriber;

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

        $menu->addChild('a2platform', [
            'label' => 'SETTINGS',
        ])->setAttribute('class', 'header');

        $menu->addChild('Settings', [
            'route' => 'admin_settings',
        ])->setLabelAttribute('icon', 'fas fa-th-large');
    }

    protected function addEntityCrudItems(ItemInterface $menu)
    {
        $entities = $this->entityHelper->getEntityList();

        $menu->addChild('entities', [
            'label' => 'ENTITIES',
        ])->setAttribute('class', 'header');

        $appEntities = array_filter($entities, function ($className) {
            return str_starts_with($className, 'App\Entity');
        });

        foreach ($appEntities as $entity) {
            $entityName = StringUtility::getShortClassName($entity);
            $menu->addChild('entities.app.' . StringUtility::toCamelCase($entityName), [
                'label' => StringUtility::normalize($entityName),
            ]);
        }

        $bundleEntities = array_filter($entities, function ($className) {
            return !str_starts_with($className, 'App\Entity');
        });

        $currentBundleName = null;

        foreach ($bundleEntities as $entity) {
            $entityName = StringUtility::getShortClassName($entity);
            $bundleName = StringUtility::getBundleNameFromClass($entity, 'Bundle');

            if ($currentBundleName != $bundleName) {
                $bundleMenuName = 'entities.bundle.' . StringUtility::toCamelCase($bundleName);
                $menu->addChild($bundleMenuName, [
                    'label' => $bundleName,
                ]);
                $subMenu = $menu->getChild($bundleMenuName);
                $currentBundleName = $bundleName;
            }
            $subMenu->addChild($bundleMenuName . '.' . StringUtility::toCamelCase($entityName), [
                'label' => StringUtility::normalize($entityName),
            ]);
        }
    }
}