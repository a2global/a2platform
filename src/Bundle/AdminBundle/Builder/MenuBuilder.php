<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Builder;

use A2Global\A2Platform\Bundle\AdminBundle\Event\EntityMenuBuildEvent;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Knp\Menu\Integration\Symfony\RoutingExtension;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuBuilder
{
    const IS_CURRENT_URL_ALIASES_KEY = 'isCurrentUriAliases';

    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected UrlGeneratorInterface    $urlGenerator,
        protected RequestStack             $requestStack,
    ) {
    }

    public function buildAdminMenu()
    {

    }

    public function buildEntityMenu($object): MenuItem
    {
        $menu = $this->getRootMenu('admin_entity_menu_' . StringUtility::toSnakeCase(get_class($object)));
        $event = new EntityMenuBuildEvent($menu, $object);
        $this->eventDispatcher->dispatch($event);
        $this->setCurrent($event->getMenu());

        return $event->getMenu();
    }

    protected function getRootMenu(string $rootName): MenuItem
    {
        $factory = new MenuFactory();
        $factory->addExtension(new RoutingExtension($this->urlGenerator));

        return $factory->createItem($rootName);
    }

    protected function setCurrent(MenuItem $menu)
    {
        $currentUrl = $this->requestStack->getMainRequest()->getPathInfo();

        foreach ($menu->getChildren() as $item) {
            if ($item->getUri() === $currentUrl) {
                $item->setCurrent(true);
                continue;
            }

            if (!array_key_exists(self::IS_CURRENT_URL_ALIASES_KEY, $item->getExtras())) {
                continue;
            }

            if (in_array($currentUrl, $item->getExtras()[self::IS_CURRENT_URL_ALIASES_KEY])) {
                $item->setCurrent(true);
                continue;
            }
        }
    }
}