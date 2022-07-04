<?php

namespace A2Global\A2Platform\Bundle\SampleBundle\Controller\Admin;

use A2Global\A2Platform\Bundle\AdminBundle\Controller\AbstractAdminController;
use A2Global\A2Platform\Bundle\SampleBundle\Entity\Author;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sample/author/', name: 'admin_sample_author_')]
class AdminAuthorController extends AbstractAdminController
{
    const RESOURCE_SUBJECT_CLASS = Author::class;
}