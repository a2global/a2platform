<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DevelopmentBundle\DevelopmentBundle;
use KevinPapst\AdminLTEBundle\Event\KnpMenuEvent;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminMenuSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected ParameterBagInterface $parameters,
    ) {
    }

    public function onSetupMenu(KnpMenuEvent $event)
    {
        if (!in_array($this->parameters->get('kernel.environment'), ['dev', 'test', 'behat'])) {
            return;
        }
        $menu = $event->getMenu();
        $menu->addChild(DevelopmentBundle::NAME, [
            'label' => StringUtility::normalize(DevelopmentBundle::NAME),
        ]);

        /** Development */

        $developmentMenu = $menu->getChild(DevelopmentBundle::NAME);
        $developmentMenu->addChild(DevelopmentBundle::NAME . '.ui', [
            'label' => 'UI sample page',
            'route' => 'admin_development_ui',
        ]);
        $developmentMenu->addChild(DevelopmentBundle::NAME . '.behat_coverage', [
            'label' => 'Behat coverage',
            'route' => 'development_behat_coverage',
        ]);
        $developmentMenu->addChild(DevelopmentBundle::NAME . '.datasheet', [
            'label' => 'Datasheet',
        ]);

        /** Datasheet */

        $developmentDatasheetMenu = $developmentMenu->getChild(DevelopmentBundle::NAME . '.datasheet');
        $developmentDatasheetMenu->addChild(DevelopmentBundle::NAME . '.datasheet.flow', [
            'label' => 'Build flow',
            'route' => 'admin_development_datasheet_flow',
        ]);
        $developmentDatasheetMenu->addChild(DevelopmentBundle::NAME . '.datasheet.array', [
            'label' => 'Array datasheet',
            'route' => 'development_behat_datasheet_array',
        ]);
        $developmentDatasheetMenu->addChild(DevelopmentBundle::NAME . '.datasheet.querybuilder.simple', [
            'label' => 'Simple QB datasheet',
            'route' => 'development_behat_datasheet_querybuilder_simple',
        ]);
        $developmentDatasheetMenu->addChild(DevelopmentBundle::NAME . '.datasheet.querybuilder.complex', [
            'label' => 'Complex QB datasheet',
            'route' => 'development_behat_datasheet_querybuilder_complex',
        ]);
        $developmentDatasheetMenu->addChild(DevelopmentBundle::NAME . '.datasheet.errors', [
            'label' => 'Errors',
            'route' => 'development_behat_datasheet_errors',
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KnpMenuEvent::class => ['onSetupMenu', 200],
        ];
    }
}