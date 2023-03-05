<?php
declare(strict_types=1);

namespace A2Global\A2Platform\Bundle\PlatformBundle\Twig;

use A2Global\A2Platform\Bundle\PlatformBundle\Builder\WorkflowTimeLineBuilder;
use A2Global\A2Platform\Bundle\PlatformBundle\Provider\FormProvider;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EntityTwigExtension extends AbstractExtension
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

        return $this->twig->render('@Platform/workflow/timeline.layout.twig', [
            'startDatetime' => $object->getCreatedAt(),
            'steps' => $timeline['steps'],
        ]);
    }
}