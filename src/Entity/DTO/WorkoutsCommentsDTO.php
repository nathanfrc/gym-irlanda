<?php

namespace App\Entity\DTO;

class WorkoutsCommentsDTO{

    private $dados = [];

    public function __construct($array)
    {
        $this->builder($array);
    }

    public  function builder($dados)
    {
        $this->dados['sucess'] = true;
        $this->dados['data']['workouts']['id'] = $dados[0]['w_id'];
        $this->dados['data']['workouts']['title'] = $dados[0]['w_title'];
        $this->dados['data']['workouts']['description'] = $dados[0]['w_description'];
        $this->dados['data']['comments'] = [];

        foreach($dados as $key => $value) {


            $newDados['id'] = $value['c_id'];
            $newDados['description'] = $value['w_description'];
            $newDados['title'] = $value['c_stars'];
            $newDados['title'] = $value['c_created'];

            $newDados['person']['id'] = $value['p_id'];
            $newDados['person']['first_name'] = $value['p_first_name'];
            $newDados['person']['last_name'] = $value['p_last_name'];
           
            array_push($this->dados['data']['comments'], $newDados);
            unset($newDados);
        }

    }

    public function getData()
    {
        return $this->dados;
    }
}