<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 09.06.2018
 * Time: 8:58
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function createIsScientistQueryBuilder()
    {
        return $this->createQueryBuilder('user')
        ->andWhere('user.isScientist = :isScientist')
        ->setParameter('isScientist', true);
    }

}