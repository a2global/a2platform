<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Doctrine\Common\Annotations\AnnotationReader;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Knp\Menu\ItemInterface;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Annotation\Route;

class KnpMenuBuilderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected $adminResourceControllers,
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
        $this->addAdminResourceControllers($menu);

//        $menu->addChild('Dashboard', [
//            'route' => 'admin_default',
//        ])->setLabelAttribute('icon', 'fas fa-th-large');

//        $menu->addChild('Website', [
//            'route' => 'website_homepage',
//        ])->setLabelAttribute('icon', 'fas fa-home');
//
//
//        $menu->addChild('KPI', [
//            'route' => 'admin_kpi',
//        ])->setLabelAttribute('icon', 'fas fa-tachometer-alt');

//        $menu->addChild('Places', [
//            'route' => 'admin_places_list',
//        ])->setLabelAttribute('icon', 'fas fa-warehouse');

//        $menu->addChild('Partnership', [
//            'route' => 'admin_partnership',
//        ])->setLabelAttribute('icon', 'fas fa-handshake');

//        $menu->addChild('Places detailed', [
//            'route' => 'admin_places_list_contacts',
//        ])->setLabelAttribute('icon', 'fas fa-th');

//        $menu->addChild('Orders', [
//            'route' => 'admin_order_list',
//        ])->setLabelAttribute('icon', 'fas fa-check-double');

//        $menu->addChild('Users', [
//            'route' => 'admin_user_list',
//        ])->setLabelAttribute('icon', 'fas fa-users');
//
//        $menu->addChild('Contacts', [
//            'route' => 'admin_contacts',
//        ])->setLabelAttribute('icon', 'fas fa-phone');

//        $menu->addChild('Geomap', [
//            'route' => 'admin_geomap',
//        ])->setLabelAttribute('icon', 'fas fa-map');
//
//        $menu->addChild('Find places', [
//            'route' => 'admin_places_find',
//        ])->setLabelAttribute('icon', 'fas fa-search');
//
//        $menu->addChild('Workflows', [
//            'route' => 'admin_workflows',
//        ])->setLabelAttribute('icon', 'fas fa-bezier-curve');
//
//        $menu->addChild('Messaging', [
//            'route' => 'admin_messaging',
//        ])->setLabelAttribute('icon', 'fas fa-comments');
//
//        $menu->addChild('Send SMS', [
//            'route' => 'admin_send_sms',
//        ])->setLabelAttribute('icon', 'fas fa-sms');
//
//        $menu->addChild('Logout', [
//            'route' => 'auth_sign_out',
//        ])->setLabelAttribute('icon', 'fas fa-power-off');

//
//
//        $menu->addChild('OrdersGroup', [
//            'label' => 'ORDERS',
//            'childOptions' => $event->getChildOptions()
//        ])->setAttribute('class', 'header');


//        $menu->addChild('blogId', [
//            'route' => 'admin_dashboard',
//            'label' => 'Dashboard',
//            'childOptions' => $event->getChildOptions(),
//            'extras' => [
//                'badge' => [
//                    'color' => 'yellow',
//                    'value' => 4,
//                ],
//            ],
//        ])->setLabelAttribute('icon', 'fas fa-tachometer-alt');

//        $menu->addChild('ChildOneItemId', [
//            'route' => 'admin_dashboard',
//            'label' => 'ChildOneDisplayName',
//            'extras' => [
//                'badges' => [
//                    [ 'value' => 6, 'color' => 'blue' ],
//                    [ 'value' => 5, ],
//                ],
//            ],
//            'childOptions' => $event->getChildOptions()
//        ])->setLabelAttribute('icon', 'fas fa-rss-square');

//        $menu->getChild('blogId')->addChild('ChildTwoItemId', [
//            'route' => 'admin_dashboard',
//            'label' => 'ChildTwoDisplayName',
//            'childOptions' => $event->getChildOptions()
//        ]);
    }

    protected function addAdminResourceControllers(ItemInterface $menu)
    {
        $menu->addChild('admin.resources', [
            'label' => 'ENTITIES',
        ])->setAttribute('class', 'header');

        $annotationReader = new AnnotationReader();

        foreach ($this->adminResourceControllers as $adminResourceController) {
            $reflectionClass = new ReflectionClass($adminResourceController);
            $annotation = $annotationReader->getClassAnnotation($reflectionClass, Route::class);
            $resourceObjectClass = constant(get_class($adminResourceController) . '::RESOURCE_SUBJECT_CLASS');
            $menu->addChild('admin.resources.' . StringUtility::toSnakeCase($resourceObjectClass), [
                'route' => $annotation->getName() . 'index',
                'label' => StringUtility::getShortClassName($resourceObjectClass),
            ]);
        }
    }
}