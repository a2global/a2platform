<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Repository;

use A2Global\A2Platform\Bundle\PlatformBundle\Entity\WorkflowTransition;
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

    public function save(WorkflowTransition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WorkflowTransition $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
