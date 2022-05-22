<?php

namespace App\Entity\DTO;

class WorkoutsDTO{

    private $dados = [];

    public function __construct($array)
    {
        $this->builder($array);
    }

    public  function builder($dados)
    {
        $this->dados['sucess'] = true;
        $this->dados['data']['id'] = $dados[0]['p_id'];
        $this->dados['data']['firstName'] = $dados[0]['first_name'];
        $this->dados['data']['lastName'] = $dados[0]['last_name'];
        $this->dados['data']['workouts'] = [];

        foreach($dados as $key => $value) {

            $newDados['id'] = $value['w_id'];
            $newDados['title'] = $value['w_title'];
            $newDados['description'] = $value['w_description'];
            array_push($this->dados['data']['workouts'], $newDados);
            unset($newDados);
        }

    }

    public function getData()
    {
        return $this->dados;
    }
}