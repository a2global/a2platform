<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Repository;

use A2Global\A2Platform\Bundle\DataBundle\Entity\TagMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TagMapping>
 *
 * @method TagMapping|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagMapping|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagMapping[]    findAll()
 * @method TagMapping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagMappingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagMapping::class);
    }

    public function add(TagMapping $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TagMapping $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TagMapping[] Returns an array of TagMapping objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TagMapping
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
