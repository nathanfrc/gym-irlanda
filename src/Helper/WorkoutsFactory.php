<?php

namespace App\Helper;

use App\Entity\Workouts;
use App\Repository\PersonRepository;
use App\Repository\UserRepository;
use App\System\DataDAO;
use App\Entity\DTO\WorkoutsDTO;

class WorkoutsFactory implements EntityFactoryInterface
{
    /**
     * @var PersonRepository
     */
    private $personRepository;

     /**
     * @var UserRepository
     */
    private $workoutsRepository;

    public function __construct(PersonRepository $personRepository, UserRepository $userRepository)
    {
        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;
    }

    public function createEntity(string $json, string $method = null, string $username = null): Workouts
    {
        $objetoJson = json_decode($json);

        $this->checkAllProperties($objetoJson);

        $workouts = new Workouts();
        $workouts
            ->setDescription($objetoJson->description)
            ->setTitle($objetoJson->title)
            ->setTime($objetoJson->time)
            //->setPerson($this->personRepository->find($objetoJson->personId))
            ->setPerson($this->checkUsernameJwt($username))
            ->setCreated($this->getDateTime());
        return $workouts;
    }

    private function checkAllProperties(object $objetoJson): void
    {
        if(is_null($objetoJson)) {
            throw new EntityFactoryException("body object can't be empty");
        }

        if (!property_exists($objetoJson, 'description')) {
            throw new EntityFactoryException("description can't be empty");
        }

        if (!property_exists($objetoJson, 'title')) {
            throw new EntityFactoryException("title can't be empty");
        }

        if (!property_exists($objetoJson, 'time')) {
            throw new EntityFactoryException("time can't be empty");
        }
    }

    private function getDateTime()
    {
        return new \DateTime('@'.strtotime('now'));
    }

    public function validateDelete(object $obj = null, $username =null){
        if(is_null($obj)) {
            throw new EntityFactoryException("Workout does not exist!");
        }
    }

    public function validateUpdate($obj, $username = null) {}

    private function checkUsernameJwt(string $user)
    {
        if(is_null(($usuarioPerson = $this->userRepository->findOneBy(['username'=>$user])))) {
            throw new EntityFactoryException("username: '".$user."' user search error JWT");
        }

        return $usuarioPerson->getPerson();
    }

    public function findByPersonWorkout(int $id)
    {
        $dataDAO = new DataDAO();

        $query = "SELECT p.first_name, p.last_name, p.id as p_id,
         w.id as w_id, w.title as w_title, w.description  as w_description
         FROM workouts as w
         JOIN person as p on p.id = w.person_id
         WHERE p.id = ? ";

         $values['id'] = $id;

         if(($result = $dataDAO->query_db($query, $values)) === false ) {
             throw new EntityFactoryException("there is no data");
         }

         $workoutsDTO = new WorkoutsDTO($result);

         return $workoutsDTO->getData();
    }

    
}
