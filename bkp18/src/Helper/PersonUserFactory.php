<?php

namespace App\Helper;

use App\Entity\User;
use App\Repository\PersonRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;

class PersonUserFactory implements EntityFactoryInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @var Person
     */
    private $person;

    private   $credentials = ['ROLER_USER'];

    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $repository, PersonRepository $personRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $repository;
        $this->personRepository = $personRepository;
    }
    
    public function createEntity(string $json, string $method = null, string $username = null): User
    {
        $objetoJson = json_decode($json);
       
        if(is_null($objetoJson->roles) || empty($objetoJson->roles))
        {
            $objetoJson->roles = ['ROLE_USER'];
        }else{
            $objetoJson->roles = ['ROLE_USER'];
        }

        $this->validate($objetoJson, $method);

        $user = new User();
        $user
            ->setUsername($objetoJson->username)
            ->setPassword($this->encoder->encodePassword($user,$objetoJson->password))
            ->setRoles($objetoJson->roles)
            ->setPerson($this->personRepository->find($objetoJson->personId));
        return $user;
    }

    private function validate($objetoJson, $method)
    {
        $this->checkAllProperties($objetoJson);

        if(!is_null($method) && $method === 'POST') {
            $this->checkUsername($objetoJson);
            $this->checkPerson($objetoJson);
            $this->checkPersonCreated($objetoJson);
        }else if(!is_null($method) && $method === 'PUT'){
            $this->checkPerson($objetoJson);
        }
    }

    private function checkAllProperties(object $objetoJson): void
    {
        if(is_null($objetoJson)) {
            throw new EntityFactoryException("body object can't be empty");
        }

        if (!property_exists($objetoJson, 'username')) {
            throw new EntityFactoryException("username can't be empty");
        }

        if (!property_exists($objetoJson, 'roles')) {
            throw new EntityFactoryException("roles can't be empty");
        }

        if (!property_exists($objetoJson, 'personId')) {
            throw new EntityFactoryException("personId can't be empty");
        }

    }

    private function validateCredentials($objetoJson)
    {
        if(!empty($objetoJson->roles)) {
            $array = $objetoJson->roles;
             if(sizeof($array) > 0) {
                for($i=0;  $i < sizeof($array); $i++) {
                    if(!in_array($array[$i], $this->credentials)) {
                        throw new EntityFactoryException("invalid credential '".$array[$i]."'
                             Ex: ROLE_USER");
                    }
                }
             }
        }
    }

    private function checkUsername(object $objetoJson)
    {
        if(!is_null($this->userRepository->findOneBy(['username'=>$objetoJson->username]))) {
            throw new EntityFactoryException("username: '".$objetoJson->username."' already exists!");
        }
    }

    private function checkPerson(object $objetoJson)
    {
        if(is_null(( $this->person = $this->personRepository->findOneBy(['id'=>$objetoJson->personId])))) {
            throw new EntityFactoryException("Person:does not exist");
        }
        
    }

    private function checkPersonCreated()
    {
        if(!is_null($this->userRepository->findOneBy(['person'=>$this->person]))) {
            throw new EntityFactoryException("user already exists for that person");
        }
    }

    public function validateDelete($obj=null, $username =null){}
    public function validateUpdate($obj=null, $username = null) {}
}
