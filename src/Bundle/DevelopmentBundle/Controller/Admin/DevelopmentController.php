<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Controller\Admin;

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

        foreach($subscribers as $subscriber){
            $subscriber = reset($subscriber);
            $subscribedEvents = [$subscriber, 'getSubscribedEvents']();
            $subscribedEvent = $subscribedEvents[OnDatasheetBuildEvent::class];
            $sortedSubscribers[get_class($subscriber)] = $subscribedEvent[1];
        }
        krsort($sortedSubscribers);
        $groupedBuilders = [];

        foreach($sortedSubscribers as $sortedSubscriber => $priority){
            $groupedBuilders[$priority][constant($sortedSubscriber. '::SUPPORTED_DATASHEET_TYPE')] =
                StringUtility::getShortClassName($sortedSubscriber);
        }

        return $this->render('@Development/behat/datasheet/flow.html.twig', [
            'groupedBuilders' => $groupedBuilders,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            EventDispatcherInterface::class,
        ]);
    }
}