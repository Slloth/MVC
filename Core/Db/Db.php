<?php

namespace Core\Db;
use PDO;
use PDOException;
use PDOStatement;

class Db extends PDO{
    
    private static $instance;

    private function __construct(){
        try{
            parent::__construct($_ENV["DSN_DATABASE"],$_ENV["USER_DATABASE"],$_ENV["PASSWORD_DATABASE"],[PDO::SQLITE_ATTR_READONLY_STATEMENT => "SET time_zone='".$_ENV["TIMEZONE"]."'"]);
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

    /**
     * Execute la requête créer préparer si il y'a des attributs.  Sinon execute simplement la requête 
     *
     * @param string $sql
     * @param array<string>|NULL $attr
     * 
     * @return PDOStatement|FALSE
     */
    public static function executePreparedQuery(string $sql, ?array $attr = NULL): PDOStatement
    {
        $db = Db::getInstance();
        $db->beginTransaction();
        if ($attr !== NULL) {
            $query = $db->prepare($sql);
            $query->execute($attr);
            }
        else {
            $query = $db->query($sql);
        }
        $db->commit();
        return $query;
    }
}