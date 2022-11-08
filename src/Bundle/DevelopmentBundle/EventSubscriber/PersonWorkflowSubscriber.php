<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\CoreBundle\Form\Type\ChoiceSearchableType;
use A2Global\A2Platform\Bundle\DataBundle\Event\Workflow\OnWorkflowTransitionFormBuild;
use A2Global\A2Platform\Bundle\DataBundle\Event\Workflow\OnWorkflowTransitionViewBuild;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Workflow\Event\TransitionEvent;

class PersonWorkflowSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.hiring.transition_form.interview_with_hr' => 'addEnglishLevelForm',
            'workflow.hiring.transition_form.provide_offer' => 'addSalaryForm',
            'workflow.hiring.transition_view.interview_with_hr' => 'viewEnglishLevel',
            'workflow.hiring.transition_view.provide_offer' => 'viewSalary',
            'workflow.hiring.transition.interview_with_hr' => 'setEnglishLevel',
            'workflow.hiring.transition.provide_offer' => 'setSalary',
        ];
    }

    public function addEnglishLevelForm(OnWorkflowTransitionFormBuild $event)
    {
        $levels = [1, 2, 3, 4, 5];
        $event->getForm()->add('englishLevel', ChoiceSearchableType::class, [
            'choices' => array_combine($levels, $levels),
        ]);
    }

    public function addSalaryForm(OnWorkflowTransitionFormBuild $event)
    {
        $event->getForm()->add('salary', TextType::class);
    }

    public function viewEnglishLevel(OnWorkflowTransitionViewBuild $event)
    {
        $event->addParameter('English level', $event->getObject()->getVersion());
    }

    public function viewSalary(OnWorkflowTransitionViewBuild $event)
    {
        $event->addParameter('Salary', $event->getObject()->getAge());
    }

    public function setEnglishLevel(TransitionEvent $event)
    {
        $event->getSubject()->setVersion($event->getContext()['englishLevel']);
    }

    public function setSalary(TransitionEvent $event)
    {
        $event->getSubject()->setAge($event->getContext()['salary']);
    }
}