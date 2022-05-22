<?php

namespace App\System;

use App\System\Connection;

abstract class Data
{
    private $conexao;

    public function __construct()
    {
        try{

            $this->conexao = Connection::getConnection();

        }catch(\Exception $e)
        {
            throw new \Exception("Database connection error =".$e->getMessage(),500);
        }
    }

    public function query_db($sql,$params=false)
    {
        try{

            if(!empty($sql))
                {
                    if($params==false)
                    {
                        $rs =  $this->conexao->query($sql);

                        unset($sql);
                        unset($param);

                    return  $retorno = $this->array_db($rs);

                    }else{

                        foreach($params as $p)
                        {
                            $param[] = $p;
                        }

                        $stmt = $this->conexao->prepare($sql);
                        $rs =  $stmt->execute($param);


                        return $retorno = $this->array_db($stmt);
                    }

                }
        }catch(\Exception $e)
        {
            throw new \Exception("Erro na execucao da query",500);
        }
    }

    public function array_db($rs)
    {

        $retorno = false;
        $i=0;
        while ($row = $rs->fetch(\PDO::FETCH_ASSOC))
        {
          $retorno[$i] = $row;
          ++$i;
        }

        return $retorno;
    }

}