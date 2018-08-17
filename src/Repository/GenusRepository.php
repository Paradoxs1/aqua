<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 24.01.2018
 * Time: 13:45
 */

namespace App\Repository;

use App\Entity\Genus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;

class GenusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genus::class);
    }

    /**
     * @return Genus[]
     */

    public function findAllPublishedOrderedByRecentlyActive()
    {

        return $this->createQueryBuilder('genus')
            ->andWhere('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->leftJoin('genus.notes', 'genus_note')
//            ->leftJoin('genus.genusScientists', 'genusScientist')
//            ->addSelect('genusScientist')
            ->orderBy('genus_note.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }

    static public function createExpertCriteria()
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->gt('yearsStudied', 20))
            ->orderBy(['yearsStudied', 'DESC']);
    }

    public function findAllExperts()
    {
        return $this->createQueryBuilder('genus')
            ->addCriteria(self::createExpertCriteria())
            ->getQuery()
            ->execute();
    }

    public function getGenusCount()
    {
        return $this->createQueryBuilder('genus')
            ->select('COUNT(genus.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPublishedGenusCount()
    {
        return $this->createQueryBuilder('genus')
            ->select('COUNT(genus.id)')
            ->where('genus.isPublished = :isPublished')
            ->setParameter('isPublished', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return Genus
     */
    public function findRandomGenus()
    {
        // very dirty way to get a "random" result - don't use in a real project!
        $results = $this->createQueryBuilder('genus')
            ->setMaxResults(10)
            ->getQuery()
            ->execute();

        return $results[array_rand($results)];
    }
}