<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use Throwable;
use Twig\Environment;

class DatasheetViewBuilder
{
    public function __construct(
        protected Environment $twig,
    ) {
    }

    public function buildDatasheet(DatasheetExposed $datasheet)
    {
        return $this->twig->render('@Data/datasheet/datasheet.html.twig', [
            'datasheet' => $datasheet,
        ]);
    }
}