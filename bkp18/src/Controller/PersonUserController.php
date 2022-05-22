<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\HypermidiaResponse;
use App\Entity\User;
use App\Helper\PersonUserFactory;
use App\Helper\RequestDataExtractor;
use App\Repository\UserRepository;
use App\Security\JwtAuthenticator;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;


class PersonUserController extends BaseController
{
    public function __construct(
        PersonUserFactory $employeeFactory,
        RequestDataExtractor $requestDataExtractor,
        UserRepository $repository,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        parent::__construct($employeeFactory, $requestDataExtractor, $repository, $cache, $logger);
    }

    public function updateExistingEntity(int $id,$entity){}

    public function existingEntityUsername(string $username){}

    public function cachePrefix(): string{ return 'personuser_'; }
}
