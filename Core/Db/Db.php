<?php

namespace Core\Db;
use PDO;
use PDOException;

class Db extends PDO{
    
    private static $instance;

    private function __construct(){
        try{
            parent::__construct($_ENV["DSN_DATABASE"],$_ENV["USER_DATABASE"],$_ENV["PASSWORD_DATABASE"]);
            //$this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND,'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
            $this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    public static function getInstance():self{
        self::$instance == NULL ? self::$instance = new self() : NULL;
        return self::$instance;
    }
}