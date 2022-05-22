<?php

namespace App\Repository;

use App\Entity\CheckWorkouts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CheckWorkouts>
 *
 * @method CheckWorkouts|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheckWorkouts|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheckWorkouts[]    findAll()
 * @method CheckWorkouts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckWorkoutsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheckWorkouts::class);
    }
}
