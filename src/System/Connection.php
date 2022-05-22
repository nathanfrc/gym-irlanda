<?php

namespace App\System;

class Connection
{
    public static $connection;

    public static function getConnection()
    {
        try { 

                $pdoConfig = Connection::getConfigEnv();

                if(!isset($connection))
                {
                    $connection =  new \PDO($pdoConfig, $_ENV['DB_USER_ADAPTER'], $_ENV['DB_PASSWORD_ADAPTER'],array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                }
                return $connection;
            
        } catch (\PDOException $e) {
           throw new \Exception("Database connection error = ".$e->getMessage(),500);
        }
    }


    private static function getConfigEnv()
    {
        Connection::validateConfigEnv();
        $pdoConfig  = $_ENV['DB_DRIVER_ADAPTER'] . ":". "host=" . $_ENV['DB_HOST_ADAPTER'] . ";";
        $pdoConfig .= "dbname=".$_ENV['DB_NAME_ADAPTER'].";charset=utf8";

        return $pdoConfig;
    }


    private static function validateConfigEnv()
    {
        if(!isset($_ENV['DB_HOST_ADAPTER'])) {
            throw new \Exception("Variable not configured in ENV  : 'DB_HOST_ADAPTER ");
        }

        if(!isset($_ENV['DB_NAME_ADAPTER'])) {
            throw new \Exception("Variable not configured in ENV  : 'DB_NAME_ADAPTER ");
        }

        if(!isset($_ENV['DB_DRIVER_ADAPTER'])) {
            throw new \Exception("Variable not configured in ENV  : 'DB_DRIVER_ADAPTER ");
        }

        if(!isset($_ENV['DB_USER_ADAPTER'])) {
            throw new \Exception("Variable not configured in ENV  : 'DB_USER_ADAPTER ");
        }

        if(!isset($_ENV['DB_PASSWORD_ADAPTER'])) {
            throw new \Exception("Variable not configured in ENV  : 'DB_PASSWORD_ADAPTER ");
        }

    }

}