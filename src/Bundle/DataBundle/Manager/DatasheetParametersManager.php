<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Manager;

use A2Global\A2Platform\Bundle\DataBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DataBundle\Filter\DataFilterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DatasheetParametersManager
{
    public function __construct(
        protected RequestStack $requestStack,
    ) {
    }

    public function getDatasheetFilterParameters(DatasheetExposed $datasheet, string $name)
    {
        $params = $this->requestStack
            ->getMainRequest()
            ->get('datasheet', []);

        return $params[$datasheet->getId()]['datasheet'][$name] ?? [];
    }

    public function applyParameters(DataFilterInterface $filter, array $parameters): DataFilterInterface
    {
        foreach ($parameters as $name => $value) {
            $setter = 'set' . $name;

            if (method_exists($filter, $setter)) {
                $filter->$setter($value);
            }
        }

        return $filter;
    }
}