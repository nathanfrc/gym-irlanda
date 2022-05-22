<?php

namespace App\Controller;

use App\Entity\HypermidiaResponse;
use App\Helper\EntityFactoryInterface;
use App\Helper\RequestDataExtractor;
use Doctrine\Common\Persistence\ObjectRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;

abstract class BaseController extends AbstractController
{
    /**
     * @var ObjectRepository
     */
    protected $repository;
    /**
     * @var EntityFactoryInterface
     */
    protected $entityFactory;
    /**
     * @var RequestDataExtractor
     */
    protected $requestDataExtractor;
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;
    /**
     * @var LoggerInterface
     */
    private $logger;

    private $userJWTCustomer;

    public function __construct(
        EntityFactoryInterface $entityFactory,
        RequestDataExtractor $requestDataExtractor,
        ObjectRepository $repository,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        $this->entityFactory = $entityFactory;
        $this->requestDataExtractor = $requestDataExtractor;
        $this->repository = $repository;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function create(Request $request): Response
    {    
        $this->userJWTCustomer = $this->getUserJwtCustomer($request);

        $entity = $this->entityFactory->createEntity($request->getContent(), $request->getMethod(), $this->userJWTCustomer);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();

        $cacheItem = $this->cache->getItem(
            $this->cachePrefix() . $entity->getId()
        );
        $cacheItem->set($entity);
        $this->cache->save($cacheItem);

        $this->logger
            ->notice(
                'Novo registro de {entidade} adicionado com id: {id}.',
                [
                    'entidade' => get_class($entity),
                    'id' => $entity->getId(),
                ]
            );

        return $this->json($entity, Response::HTTP_CREATED);
    }

    public function findByAll(Request $request): Response
    {
        try {
            
            $filterData = $this->requestDataExtractor->getFilterData($request);

            if(isset($filterData["url"])) {
                unset($filterData["url"]);
            }
            
            $orderData = $this->requestDataExtractor->getOrderData($request);
            
            $paginationData = $this->requestDataExtractor->getPaginationData($request);
            
            $itemsPerPage = $request->query->getInt('itemsPerPage', 10);

            if(isset($filterData["itemsPerPage"])) {
                unset($filterData["itemsPerPage"]);
            }
        
            $entityList = $this->repository->findBy(
                $filterData,
                $orderData,
                $itemsPerPage,
                ($paginationData - 1) * $itemsPerPage
            );
            
            $hypermidiaResponse = new HypermidiaResponse($entityList, true, Response::HTTP_OK, $paginationData, $itemsPerPage);
        } catch (\Throwable $erro) {
            $hypermidiaResponse = HypermidiaResponse::fromError($erro);
        }

        return $hypermidiaResponse->getResponse();
    }

    public function findById(int $id)
    {
        $entity = $this->cache->hasItem($this->cachePrefix() . $id)
            ? $this->cache->getItem($this->cachePrefix() . $id)->get()
            : $this->repository->find($id);
        $hypermidiaResponse = new HypermidiaResponse($entity, true, Response::HTTP_OK, null);

        return $hypermidiaResponse->getResponse();
    }

    public function update(int $id, Request $request): Response
    {
        $this->userJWTCustomer = $this->getUserJwtCustomer($request);
        
        $entity = $this->entityFactory->createEntity($request->getContent(), $request->getMethod(), $this->userJWTCustomer);

        if(is_null($entity->getId()))
        {
            $entityValid = $this->repository->find($id);
        }

        $this->userJWTCustomer = $this->getUserJwtCustomer($request);

        if(isset($entityValid)) {
            $this->entityFactory->validateUpdate($entityValid, $this->userJWTCustomer);
        }else{
            $this->entityFactory->validateUpdate($entity, $this->userJWTCustomer);
        }

        $existingEntity = $this->updateExistingEntity($id, $entity);

        $this->getDoctrine()->getManager()->flush();

        $cacheItem = $this->cache->getItem($this->cachePrefix() . $id);
        $cacheItem->set($existingEntity);
        $this->cache->save($cacheItem);

        return $this->json($existingEntity);
    }

    public function delete(int $id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entity = $this->repository->find($id);
        
        $this->userJWTCustomer = $this->getUserJwtCustomer($request);
        $this->entityFactory->validateDelete($entity, $this->userJWTCustomer);

       if(is_null($entity = $this->repository->find($id))) {
           return new Response('', Response::HTTP_NOT_FOUND);
       } 

        $entityManager->remove($entity);
        $entityManager->flush();

        $this->cache->deleteItem($this->cachePrefix() . $id);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    public function getUserJwtCustomer(Request $request)
    {
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization'));
        $user = JWT::decode($token, $_ENV['JWT_KEY'], ['HS256']);
        return isset($user->username) ? $user->username : null;
    }
    public function responseData($dados, int $status=200,array $headers = [])
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8', true);
        die(json_encode($this->array_map_deep_atualizado($dados, 'utf8_encode')));
    }

    function array_map_deep_atualizado($array, $callback)
    {
        $new = array();

        if(empty($array)){
            return [];
        }

        foreach($array as $key => $val)
        {
            if (is_array($val))
            {
                $new[$key] = $this->array_map_deep_atualizado($val, $callback);
            } else if (is_int($val)) {
                $new[$key] = (int) \call_user_func($callback, $val);
            }else if(is_float($val)){
                $new[$key] = (float) \call_user_func($callback, $val);
            }else if(is_null($val)){
                $new[$key] = null;
            }else if(is_bool($val)){
                if($val === true){
                    $new[$key] = true;
                }else{
                    $new[$key] = false;
                }
            }else if (is_double($val)){
                $new[$key] = (double)  \call_user_func($callback, $val);
            }else{
                $new[$key] = \utf8_decode(\call_user_func($callback, $val));
            }
        }

        return $new;
    }

    abstract public function updateExistingEntity(int $id, $entity);
    abstract public function cachePrefix(): string;
}
