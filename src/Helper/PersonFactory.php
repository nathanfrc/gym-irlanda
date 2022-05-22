<?php

namespace App\Helper;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Repository\UserRepository;

class PersonFactory implements EntityFactoryInterface
{
    /**
     * @var PersonRepository
     */
    private $personRepository;

     /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(PersonRepository $personRepository, UserRepository $userRepository)
    {
        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;
    }

    public function createEntity(string $json, string $method = null, string $username =null): Person
    {
        $objetoJson = json_decode($json);

        $this->validate($objetoJson, $method);

        $person = new Person();
        $person
            ->setFirstName($objetoJson->firstName)
            ->setLastName($objetoJson->lastName)
            ->setNumberId($objetoJson->numberId)
            ->setEmail($objetoJson->email)
            ->setHeigth($objetoJson->heigth)
            ->setWeight($objetoJson->weight)
            ->setCreated($this->getDateTime());
        return $person;
    }


    private function validate(object $objetoJson, $method)
    {
        $this->checkAllProperties($objetoJson);

        if(!is_null($method) && $method === 'POST') {
            $this->checkNumberId($objetoJson);
            $this->checkEmail($objetoJson);
        }
       
    }

    private function checkAllProperties(object $objetoJson): void
    {
        if(is_null($objetoJson)) {
            throw new EntityFactoryException("body object can't be empty");
        }

        if (!property_exists($objetoJson, 'firstName')) {
            throw new EntityFactoryException("firstName can't be empty");
        }

        if (!property_exists($objetoJson, 'lastName')) {
            throw new EntityFactoryException("lastName can't be empty");
        }

        if (!property_exists($objetoJson, 'numberId')) {
            throw new EntityFactoryException("numberId can't be empty");
        }

        if (!property_exists($objetoJson, 'email')) {
            throw new EntityFactoryException("email can't be empty");
        }

        if(!filter_var($objetoJson->email, FILTER_VALIDATE_EMAIL)) {
            throw new EntityFactoryException("email is not valid");
        }
    }

    private function getDateTime()
    {
        return new \DateTime('@'.strtotime('now'));
    }

   
    private function checkNumberId(object $objetoJson)
    {
        if(!is_null($this->personRepository->findOneBy(['numberId'=>$objetoJson->numberId]))) {
            throw new EntityFactoryException("numberId: '".$objetoJson->numberId."' already exists!");
        }
    }

    private function checkEmail(object $objetoJson)
    {
        if(!is_null($this->personRepository->findOneBy(['email'=>$objetoJson->email]))) {
            throw new EntityFactoryException("email: '".$objetoJson->email."' already exists!");
        }
    }

    public function validateDelete(object $person, $username =null)
    {
        if(!is_null(($person = $this->userRepository->findOneBy(['person'=> $person])))) {
            throw new EntityFactoryException("first delete the user then the person the endpoint with method DELETE /user/" . $person->getId());
        }
    }

    public function validateUpdate($obj, $username = null) {}
}
