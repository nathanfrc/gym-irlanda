<?php

namespace App\Helper;

use App\Entity\CheckWorkouts;
use App\Entity\DTO\findbyPersonWorkouts;
use App\Repository\CheckWorkoutsRepository;
use App\Repository\PersonRepository;
use App\Repository\WorkoutsRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\System\DataDAO;
use App\Entity\DTO\PersonWorkoutsDTO;

class CheckWorkoutsFactory implements EntityFactoryInterface
{
    /**
     * @var PersonRepository
     */
    private $personRepository;
     /**
     * @var WorkoutsRepository
     */
    private $workoutsRepository;
      /**
     * @var CheckWorkoutsRepository
     */
    private $checkWorkoutsRepository;

     /**
     * @var UserRepository
     */
    private $userRepository;
   
    public function __construct(
        PersonRepository $personRepository, 
        WorkoutsRepository $workoutsRepository,
        CheckWorkoutsRepository $checkWorkoutsRepository,
        UserRepository $userRepository
        )
    {
        $this->personRepository = $personRepository;
        $this->workoutsRepository = $workoutsRepository;
        $this->checkWorkoutsRepository = $checkWorkoutsRepository;
        $this->userRepository = $userRepository;
    }

    public function createEntity(string $json, string $method = null, string $username = null): CheckWorkouts
    {
        $objetoJson = json_decode($json);

        $this->validate($objetoJson, $method);

        $comments = new CheckWorkouts();
        $comments
            ->setTrainingCheck($this->createOrUpdate($objetoJson, $method))
           // ->setPerson($this->personRepository->find($objetoJson->personId))
            ->setPerson($this->checkUsernameJwt($username))
            ->setWorkouts($this->workoutsRepository->find($objetoJson->workoutsId))
            ->setCreated($this->getDateTime());
        return $comments;
    }

    private function createOrUpdate($objetoJson, $method)
    {
        if(!is_null($method) && $method === 'POST') {
           return $this->createDaysCheck($objetoJson);
        }else if(!is_null($method) && $method === 'PUT'){
            return json_encode($objetoJson->training_check, true);
        }
    }

    private function checkAllPropertiesUpdate($objetoJson)
    {
        if (!property_exists($objetoJson, 'training_check')) {
            throw new EntityFactoryException("training_check can't be empty");
        }

        if (!property_exists($objetoJson, 'workoutsId')) {
            throw new EntityFactoryException("workoutsId can't be empty");
        }

    }

    private function validate(object $objetoJson, $method)
    {
        if(!is_null($method) && $method === 'POST') {
            $this->checkAllProperties($objetoJson);
            $this->checkWorkoutsIdExist($objetoJson->workoutsId);
        }else if(!is_null($method) && $method === 'PUT'){
            $this->checkAllPropertiesUpdate($objetoJson);
        }
    }

    private function checkAllProperties(object $objetoJson): void
    {
        if(is_null($objetoJson)) {
            throw new EntityFactoryException("body object can't be empty");
        }

        if (!property_exists($objetoJson, 'workoutsId')) {
            throw new EntityFactoryException("workoutsId can't be empty");
        }

    }

    private function getDateTime()
    {
        return new \DateTime('@'.strtotime('now'));
    }

    private function checkPersonIdExist(int $id)
    {
        if(is_null(($person = $this->personRepository->find($id)))) {
            throw new EntityFactoryException("personId: '".$id."' person does not exist!");
        }

        return $person;
    }

    private function checkWorkoutsIdExist(int $id)
    {
        if(is_null(($workouts = $this->workoutsRepository->find($id)))) {
            throw new EntityFactoryException("workoutsId: '".$id."' workouts does not exist!");
        }

        return $workouts;
    }

    private function createDaysCheck(object $objetoJson)
    {
        $day = 'day';
        $y=0;
        $days = [];

        for ($i = 0; $i < $this->timeWorkouts($objetoJson); $i ++) {
            $days[$day.++$y] = false;
        }

        return json_encode($days, true);
    }

    private function timeWorkouts(object $objetoJson)
    {
       $workouts = $this->checkWorkoutsIdExist($objetoJson->workoutsId);
       return $workouts->getTime();
    }

    private function checkUsernameJwt(string $user)
    {
        if(is_null(($usuarioPerson = $this->userRepository->findOneBy(['username'=>$user])))) {
            throw new EntityFactoryException("username: '".$user."' user search error JWT");
        }

        return $usuarioPerson->getPerson();
    }

    public function findbyPersonWorkouts(int $id)
    {
            $data = new DataDAO();
    
            $query = "SELECT p.first_name, p.last_name,p.id AS idPerson, w.id AS idWorkouts,
                cw.created,cw.training_check, w.title  AS w_title,w.description AS w_description,
                cw.id AS cw_id, cw.training_check
                FROM check_workouts AS cw 
                left JOIN workouts  AS w ON w.id = cw.workouts_id
                JOIN person AS p ON p.id = cw.person_id 
                where p.id = ?";
                $values['id'] = $id;
    
            if(($dataReturn = $data->query_db($query, $values)) === false) {
                throw new EntityFactoryException("there is no data");
            }

            $personWorkoutsDTO  = new PersonWorkoutsDTO($dataReturn);

            return $personWorkoutsDTO->getData();
    }

    public function validateDelete(object $obj = null, $username =null)
    {
        if(is_null($obj)) {
            throw new EntityFactoryException("checkworkout does not exist!");
        }

        $person = $this->checkUsernameJwt($username);

        $data = new DataDAO();
    
        $query = "SELECT * 
        FROM check_workouts 
        WHERE id = ?  AND person_id = ?";
            $values['id'] = $obj->getId();
            $values['person_id'] =  $person->getId();

        if(($dataReturn = $data->query_db($query, $values)) === false) {
            throw new EntityFactoryException("you you are not allowed to delete checkWorkouts that are not yours");
        }
        
       return;
    }

    public function validateUpdate(object $obj, $username = null) 
    {
        $person = $this->checkUsernameJwt($username);
        $data = new DataDAO();
        $query = "SELECT * 
        FROM check_workouts 
        WHERE id = ?  AND person_id = ?";
            $values['id'] = $obj->getId();
            $values['person_id'] =  $person->getId(); 

        if(($dataReturn = $data->query_db($query, $values)) === false) {
            throw new EntityFactoryException("you you are not allowed to update checkWorkouts that are not yours:".$obj->getId());
        }
        
       return;
    }
}
