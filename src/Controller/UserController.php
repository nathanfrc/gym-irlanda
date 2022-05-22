<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\HypermidiaResponse;
use App\Entity\User;
use App\Helper\UserFactory;
use App\Helper\RequestDataExtractor;
use App\Repository\UserRepository;
use App\Security\JwtAuthenticator;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;


class UserController extends BaseController
{

    public function __construct(
        UserFactory $employeeFactory,
        RequestDataExtractor $requestDataExtractor,
        UserRepository $repository,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($employeeFactory, $requestDataExtractor, $repository, $cache, $logger);
    }


    public function updateExistingEntity(int $id,$entity)
    {
        /** @var User $userExistente */
        $userExistente = $this->getDoctrine()->getRepository(User::class)->find($id);
        $userExistente->setUsername($entity->getUsername());
        $userExistente->setRoles($entity->getRoles());
        $userExistente->setPassword($entity->getPassword());
        return $userExistente;
    }


    public function existingEntityUsername(string $username)
    {
        /** @var User $userExistente */
        $userExistente = $this->getDoctrine()
            ->getRepository(User::class)->findOneBy(['username'=>$username]);

        return $userExistente;
    }

    public function cachePrefix(): string
    {
        return 'user_';
    }
}
