<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Controller\Admin;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\ChartDataHelper;
use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use A2Global\A2Platform\Bundle\DataBundle\Event\Datasheet\OnDatasheetBuildEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/development/", name="admin_development_")
 */
class DevelopmentController extends AbstractController
{
    /**
     * @Route("datasheet/flow", name="datasheet_flow")
     */
    public function datasheetFlowAction()
    {
        $sortedSubscribers = [];
        $subscribers = $this->get(EventDispatcherInterface::class)
            ->getListeners(OnDatasheetBuildEvent::class);

        foreach ($subscribers as $subscriber) {
            $subscriber = reset($subscriber);
            $subscribedEvents = [$subscriber, 'getSubscribedEvents']();
            $subscribedEvent = $subscribedEvents[OnDatasheetBuildEvent::class];
            $sortedSubscribers[get_class($subscriber)] = $subscribedEvent[1];
        }
        arsort($sortedSubscribers);
        $groupedBuilders = [];

        foreach ($sortedSubscribers as $sortedSubscriber => $priority) {
            $groupedBuilders[$priority][] = [
                'supportedDatasheet' => constant($sortedSubscriber . '::SUPPORTED_DATASHEET_TYPE'),
                'builder' => StringUtility::getShortClassName($sortedSubscriber),
            ];
        }

        return $this->render('@Development/behat/datasheet/flow.html.twig', [
            'groupedBuilders' => $groupedBuilders,
        ]);
    }

    /**
     * @Route("ui", name="ui")
     */
    public function uiAction()
    {
        $statistix = [
            'Jan 2022' => [
                'Income' => 22,
                'Outcome' => 18,
            ],
            'Feb 2022' => [
                'Income' => 32,
                'Outcome' => 27,
            ],
            'Mar 2022' => [
                'Income' => 56,
                'Outcome' => 53,
            ],
        ];

        return $this->render('@Development/behat/datasheet/ui.html.twig', [
            'chartData' => ChartDataHelper::buildFromArray($statistix),
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            EventDispatcherInterface::class,
        ]);
    }
}