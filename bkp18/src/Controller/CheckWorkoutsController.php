<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Helper\CheckWorkoutsFactory;
use App\Helper\RequestDataExtractor;
use App\Repository\CheckWorkoutsRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use App\Entity\CheckWorkouts;
use Symfony\Component\HttpFoundation\Request;

class CheckWorkoutsController extends BaseController
{
    public function __construct(
        CheckWorkoutsFactory $factory,
        RequestDataExtractor $requestDataExtractor,
        CheckWorkoutsRepository $repository,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($factory, $requestDataExtractor, $repository, $cache, $logger);
    }

    public function updateExistingEntity(int $id, $entity)
    {
        /** @var CheckWorkouts $objExistente */

        $objExistente = $this->getDoctrine()->getRepository(CheckWorkouts::class)->find($id);
        $objExistente->setTrainingCheck($entity->getTrainingCheck());
    
        return $objExistente;
    }

    public function cachePrefix(): string
    {
        return 'checkworkouts_';
    }


    public function findbyPersonWorkouts(int $id)
    {
       $result =  $this->entityFactory->findbyPersonWorkouts($id);
       $this->responseData($result);
    }

}
