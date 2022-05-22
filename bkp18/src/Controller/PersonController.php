<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Person;
use App\Helper\PersonFactory;
use App\Helper\RequestDataExtractor;
use App\Repository\PersonRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class PersonController extends BaseController
{
    public function __construct(
        PersonFactory $factory,
        RequestDataExtractor $requestDataExtractor,
        PersonRepository $repository,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($factory, $requestDataExtractor, $repository, $cache, $logger);
    }

    public function updateExistingEntity(int $id, $entity)
    {
        /** @var Person $objExistente */

        $objExistente = $this->getDoctrine()->getRepository(Person::class)->find($id);
        $objExistente->setFirstName($entity->getFirstName());
        $objExistente->setLastName($entity->getLastName());
        $objExistente->setNumberId($entity->getNumberId());
        $objExistente->setEmail($entity->getEmail());
        $objExistente->setHeigth($entity->getHeigth());
        $objExistente->setWeight($entity->getWeight());

        return $objExistente;
    }

    public function cachePrefix(): string
    {
        return 'person_';
    }
}
