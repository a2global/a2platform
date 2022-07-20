<?php

namespace A2Global\A2Platform\Bundle\AdminBundle\Controller;

use A2Global\A2Platform\Bundle\DatasheetBundle\Builder\DatasheetBuilder;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="_default")
     */
    public function homepageAction()
    {
        $books = [
            ['name' => 'A Tale of Two Cities', 'author' => 'Charles Dickens', 'year' => 1859, 'sold' => 200,],
            ['name' => 'The Little Prince', 'author' => 'Antoine de Saint-Exupéry', 'year' => 1943, 'sold' => 200,],
            ['name' => 'Harry Potter and the Philosopher`s Stone', 'author' => 'J. K. Rowling', 'year' => 1997, 'sold' => 120,],
            ['name' => 'The Hobbit', 'author' => 'J. R. R. Tolkien', 'year' => 1937, 'sold' => 100,],
            ['name' => 'Dream of the Red Chamber', 'author' => 'Cao Xueqin', 'year' => 1791, 'sold' => 100,],
            ['name' => 'And Then There Were None', 'author' => 'Agatha Christie', 'year' => 1939, 'sold' => 100,],
        ];

        $booksDatasheet = new Datasheet($books);

        return $this->render('@Admin/homepage.html.twig', [
//            'booksDatasheet' => $booksDatasheet,
        ]);
    }

    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            DatasheetBuilder::class,
        ]);
    }
}