<?php

namespace App\Repository;

use App\Entity\Workouts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workouts>
 *
 * @method Workouts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workouts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workouts[]    findAll()
 * @method Workouts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkoutsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workouts::class);
    }
}
