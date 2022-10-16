<?php

namespace A2Global\A2Platform\Bundle\DataBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Entity\BaseUser;
use A2Global\A2Platform\Bundle\CoreBundle\Entity\Setting;
use A2Global\A2Platform\Bundle\CoreBundle\Helper\EntityHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Knp\Menu\ItemInterface;
use League\Bundle\OAuth2ServerBundle\Model\AbstractClient;
use League\Bundle\OAuth2ServerBundle\Model\AccessToken;
use League\Bundle\OAuth2ServerBundle\Model\AuthorizationCode;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\Model\RefreshToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminMenuBuilderSubscriber implements EventSubscriberInterface
{
    private const EXCLUDED_ENTITIES = [
        BaseUser::class,
        Setting::class,
        AbstractClient::class,
        AccessToken::class,
        AuthorizationCode::class,
        Client::class,
        RefreshToken::class,
    ];

    public function __construct(
        protected EntityHelper $entityHelper,
        protected TranslatorInterface $translator,
    ) {
    }

    /** @codeCoverageIgnore */
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
            'label' => 'data.admin_menu.data',
        ])->setAttribute('class', 'header');

        $appEntities = array_filter($entities, function ($className) {
            return str_starts_with($className, 'App\Entity');
        });
        sort($appEntities);

        foreach ($appEntities as $entity) {
            $this->addEntityCrudItem($menu, 'data.entities.app', $entity);
        }
        $bundleEntities = array_filter($entities, function ($className) {
            return !str_starts_with($className, 'App\Entity');
        });
        sort($bundleEntities);
        $currentBundleName = null;

        foreach ($bundleEntities as $entity) {
            if (in_array($entity, self::EXCLUDED_ENTITIES)) {
                continue;
            }
            $bundleName = StringUtility::getBundleNameFromClass($entity, 'Bundle');

            if ($currentBundleName != $bundleName) {
                $bundleMenuName = 'data.entities.bundle.' . StringUtility::toCamelCase($bundleName);
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
            'label' => 'data.entity.' . StringUtility::toCamelCase($entityName) . '.title',
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