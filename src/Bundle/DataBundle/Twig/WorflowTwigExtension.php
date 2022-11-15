<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Twig;

use A2Global\A2Platform\Bundle\DataBundle\Builder\WorkflowTimeLineBuilder;
use A2Global\A2Platform\Bundle\DataBundle\Provider\FormProvider;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WorflowTwigExtension extends AbstractExtension
{
    public function __construct(
        protected FormProvider            $formProvider,
        protected Environment             $twig,
        protected WorkflowTimeLineBuilder $workflowTimeLineBuilder,
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('workflowTimeline', [$this, 'getWorkflowTimeline'], ['is_safe' => ['html']]),
        ];
    }

    public function getWorkflowTimeline($object, $workflowName = null)
    {
        $timeline = $this->workflowTimeLineBuilder->build($object, $workflowName);

        return $this->twig->render('@Data/workflow/timeline.layout.twig', [
            'startDatetime' => $object->getCreatedAt(),
            'steps' => $timeline['steps'],
        ]);
    }
}