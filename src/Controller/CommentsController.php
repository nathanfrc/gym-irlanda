<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Comments;
use App\Helper\CommentsFactory;
use App\Helper\RequestDataExtractor;
use App\Repository\CommentsRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class CommentsController extends BaseController
{
    public function __construct(
        CommentsFactory $factory,
        RequestDataExtractor $requestDataExtractor,
        CommentsRepository $repository,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($factory, $requestDataExtractor, $repository, $cache, $logger);
    }

    public function updateExistingEntity(int $id, $entity)
    {
        /** @var Comments $objExistente */
        $objExistente = $this->getDoctrine()->getRepository(Comments::class)->find($id);
        $objExistente->setDescription($entity->getDescription());
        
        return $objExistente;
    }

    public function cachePrefix(): string
    {
        return 'workouts_';
    }

    public function findByWorkoutsComments(int $id)
    {
        return $this->responseData( $this->entityFactory->findByWorkoutsComments($id));
    }
}
