<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\EventSubscriber;

use A2Global\A2Platform\Bundle\DataBundle\Event\Workflow\OnWorkflowTransitionFormBuild;
use A2Global\A2Platform\Bundle\DataBundle\Event\Workflow\OnWorkflowTransitionViewBuild;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            'workflow.person.transition_form.interview_with_hr' => 'addEnglishLevelForm',
            'workflow.person.transition_form.provide_offer' => 'addSalaryForm',
            'workflow.person.transition_view.interview_with_hr' => 'viewEnglishLevel',
            'workflow.person.transition_view.provide_offer' => 'viewSalary',
            'workflow.person.transition.interview_with_hr' => 'setEnglishLevel',
            'workflow.person.transition.provide_offer' => 'setSalary',
        ];
    }

    public function addEnglishLevelForm(OnWorkflowTransitionFormBuild $event)
    {
        $event->getForm()->add('englishLevel', ChoiceType::class, [
            'choices' => [1, 2, 3, 4, 5]
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