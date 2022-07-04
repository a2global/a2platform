<?php

namespace A2Global\A2Platform\Bundle\SampleBundle\Controller;

use A2Global\A2Platform\Bundle\CoreBundle\Controller\ResourceController;
use A2Global\A2Platform\Bundle\SampleBundle\Entity\Book;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'book')]
class BookController extends ResourceController
{
    const RESOURCE_SUBJECT_CLASS = Book::class;
}