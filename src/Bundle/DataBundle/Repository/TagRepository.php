<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Repository;

use A2Global\A2Platform\Bundle\DataBundle\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getSuggestions($term, $existingTags = [])
    {
        $qb = $this
            ->createQueryBuilder('t')
            ->select('t.name')
            ->where('t.name LIKE :term')
            ->setParameter('term', '%' . $term . '%');

        if (count($existingTags) > 0) {
            $qb
                ->andWhere('t.name NOT IN (:existing)')
                ->setParameter('existing', $existingTags);
        }

//        $query = $qb->getQuery()->getSQL();
        return $qb
            ->orderBy('t.name')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
