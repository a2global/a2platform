<?php

namespace A2Global\A2Platform\Bundle\SampleBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Controller\ResourceController;
use A2Global\A2Platform\Bundle\SampleBundle\Entity\Author;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author', name: 'author')]
class AuthorController extends ResourceController
{
    const RESOURCE_SUBJECT_CLASS = Author::class;
}