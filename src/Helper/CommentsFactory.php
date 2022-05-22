<?php

namespace App\Helper;

use App\Entity\Comments;
use App\Repository\PersonRepository;
use App\Repository\UserRepository;
use App\Repository\WorkoutsRepository;
use App\System\DataDAO;

class CommentsFactory implements EntityFactoryInterface
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
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        PersonRepository $personRepository,
        WorkoutsRepository $workoutsRepository,
        UserRepository $userRepository )
    {
        $this->personRepository = $personRepository;
        $this->workoutsRepository = $workoutsRepository;
        $this->userRepository = $userRepository;
    }

    public function createEntity(string $json, string $method = null, string $username = null): Comments
    {
        $objetoJson = json_decode($json);

        $this->validate($objetoJson, $method);

        $comments = new Comments();
        $comments
            ->setDescription($objetoJson->description)
            ->setStars($objetoJson->stars)
            //->setPerson($this->personRepository->find($objetoJson->personId))
            ->setPerson($this->checkUsernameJwt($username))
            ->setWorkouts($this->workoutsRepository->find($objetoJson->workoutsId))
            ->setCreated($this->getDateTime());
        return $comments;
    }


    private function validate(object $objetoJson, $method)
    {
        $this->checkAllProperties($objetoJson);

        if(!is_null($method) && $method === 'POST') {
            $this->checkWorkoutsIdExist($objetoJson);
        }
       
    }

    private function checkAllProperties(object $objetoJson): void
    {
        if(is_null($objetoJson)) {
            throw new EntityFactoryException("body object can't be empty");
        }

        if (!property_exists($objetoJson, 'description')) {
            throw new EntityFactoryException("description can't be empty");
        }

        if (!property_exists($objetoJson, 'stars')) {
            throw new EntityFactoryException("stars can't be empty");
        }else{

            if($objetoJson->stars > 5)
            {
                throw new EntityFactoryException("maximum five stars");
            }else if($objetoJson->stars == 0)
            {
                throw new EntityFactoryException("minimum zero stars");
            }
        }

    }

    private function getDateTime()
    {
        return new \DateTime('@'.strtotime('now'));
    }

   
    private function checkPersonIdExist(object $objetoJson)
    {
        if(is_null($this->personRepository->find($objetoJson->personId))) {
            throw new EntityFactoryException("personId: '".$objetoJson->personId."' person does not exist!");
        }
    }

    private function checkWorkoutsIdExist(object $objetoJson)
    {
        if(is_null($this->workoutsRepository->find($objetoJson->workoutsId))) {
            throw new EntityFactoryException("workoutsId: '".$objetoJson->workoutsId."' workouts does not exist!");
        }
    }

    private function checkUsernameJwt(string $user)
    {
        if(is_null(($usuarioPerson = $this->userRepository->findOneBy(['username'=>$user])))) {
            throw new EntityFactoryException("username: '".$user."' user search error JWT");
        }

        return $usuarioPerson->getPerson();
    }

    public function validateDelete($obj, $username = null) 
    {
        $person = $this->checkUsernameJwt($username);

        $data = new DataDAO();
    
        $query = "SELECT * FROM comments
            WHERE id = ? AND person_id = ?";
            $values['id'] = $obj->getId();
            $values['person_id'] =  $person->getId();

        if(($dataReturn = $data->query_db($query, $values)) === false) {
            throw new EntityFactoryException("you you are not allowed to delete comments that are not yours");
        }
        
       return;
    }

    public function validateUpdate($obj, $username = null) 
    {
        $person = $this->checkUsernameJwt($username);

        $data = new DataDAO();
    
        $query = "SELECT * FROM comments
            WHERE id = ? AND person_id = ?";
            $values['id'] = $obj->getId();
            $values['person_id'] =  $person->getId();

        if(($dataReturn = $data->query_db($query, $values)) === false) {
            throw new EntityFactoryException("you you are not allowed to update comments that are not yours");
        }
        
       return;
    }

}
