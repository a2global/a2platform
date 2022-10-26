<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Repository;

use A2Global\A2Platform\Bundle\DataBundle\Entity\WorkflowTransition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WorkflowTransition>
 *
 * @method WorkflowTransition|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkflowTransition|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkflowTransition[]    findAll()
 * @method WorkflowTransition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkflowTransitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowTransition::class);
    }
}
