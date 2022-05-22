<?php

namespace App\Entity\DTO;

class PersonWorkoutsDTO{

    private $dados = [];

    public function __construct($array)
    {
        $this->builder($array);
    }

    public  function builder($dados)
    {
        $this->dados['sucess'] = true;
        $this->dados['data']['id'] = $dados[0]['idPerson'];
        $this->dados['data']['firstName'] = $dados[0]['first_name'];
        $this->dados['data']['lastName'] = $dados[0]['last_name'];
        $this->dados['data']['workouts'] = [];

        foreach($dados as $key => $value) {

            $newDados['id'] = $value['idWorkouts'];
            $newDados['title'] = $value['w_title'];
            $newDados['description'] = $value['w_description'];

            $newDados['checkworkouts']['id'] = $value['cw_id'];

            if(!is_null($value['training_check'])) {
                $newDados['checkworkouts']['training_check'] = \json_decode($value['training_check'],true);
            }else{
                $newDados['checkworkouts']['training_check'] = [];
            }

            array_push($this->dados['data']['workouts'], $newDados);
            unset($newDados);
        }

    }

    public function getData()
    {
        return $this->dados;
    }
}