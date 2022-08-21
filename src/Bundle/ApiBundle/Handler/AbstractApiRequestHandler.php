<?php

namespace A2Global\A2Platform\Bundle\ApiBundle\Handler;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AbstractApiRequestHandler
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    public function getRepository(Request $request)
    {
        $entityNameSnakeCase = mb_substr(
            $request->attributes->get('_route'),
            mb_strlen($this->getRouteNamePrefix())
        );
        $entityClassName = sprintf('App\\Entity\\%s', StringUtility::toPascalCase($entityNameSnakeCase));

        if (!class_exists($entityClassName)) {
            throw new NotFoundHttpException();
        }

        return $this->entityManager->getRepository($entityClassName);
    }
}