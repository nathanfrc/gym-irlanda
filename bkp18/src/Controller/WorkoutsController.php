<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Workouts;
use App\Helper\WorkoutsFactory;
use App\Helper\RequestDataExtractor;
use App\Repository\WorkoutsRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class WorkoutsController extends BaseController
{
    public function __construct(
        WorkoutsFactory $factory,
        RequestDataExtractor $requestDataExtractor,
        WorkoutsRepository $repository,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($factory, $requestDataExtractor, $repository, $cache, $logger);
    }

    public function updateExistingEntity(int $id, $entity)
    {
        /** @var Workouts $objExistente */

        $objExistente = $this->getDoctrine()->getRepository(Workouts::class)->find($id);
        $objExistente->setDescription($entity->getDescription());
        $objExistente->setTitle($entity->getTitle());
        $objExistente->setTime($entity->getTime());

        return $objExistente;
    }

    public function cachePrefix(): string
    {
        return 'workouts_';
    }

    public function findByPersonWorkout(int $id)
    {
        return $this->responseData( $this->entityFactory->findByPersonWorkout($id));
    }
}
