<?php

abstract class AbstractModel{

    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;
    private $db_port;

    private $table;
    private $id;

    //connexion string
    protected $_connexion;

    public function __construct()
    {
        // On récupère les données du .env.local pour initialiser les attributs
        $this->db_host = $_ENV["HOST_DATABASE"];
        $this->db_name = $_ENV["NAME_DATABASE"];
        $this->db_user = $_ENV["USER_DATABASE"];
        $this->db_password = $_ENV["PASSWORD_DATABASE"];
        $this->db_port = $_ENV["PASSWORD_DATABASE"];
    }

    // On ferme la connexion avant d'ouvrire une nouvelle et on créer une connexion mysqli
    public function getConnection(){
        $this->_connexion = null;
        try{
            $this->_connexion = new mysqli($this->db_host,$this->db_user, $this->db_password,$this->db_name,(int)$this->db_port);
        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
    }

    public function setTable(string $table):void{
        $this->table = $table;
    }

    
    public function setId(string $id):void{
        $this->id = $id;
    }

    // Requête pour récupèrer toutes les lignes d'une table
    public function getAll(){
        $sql = "SELECT * FROM ". $this->table;
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        $this->_connexion->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Requête pour récupèrer une ligne d'une table
    public function getOne(){
        $sql = "SELECT * FROM ". $this->table . "WHERE id =" .$this->id;
        $query = $this->_connexion->prepare($sql);
        $query->execute();
        $result = $query->get_result();
        $this->_connexion->close();
        return $result->fetch_one();
    }
}