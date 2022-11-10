<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Twig;

use A2Global\A2Platform\Bundle\AdminBundle\Builder\MenuBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminTwigExtension extends AbstractExtension
{
    public function __construct(
        protected MenuBuilder $menuBuilder,
    )
    {

    }

    public function getFunctions()
    {
        return [
            new TwigFunction('admin_entity_menu', [$this, 'getAdminEntityMenu']),
        ];
    }

    public function getAdminEntityMenu($object)
    {
        return $this->menuBuilder->buildEntityMenu($object);
    }
}