<?php

abstract class AbstractModel{

    private $db_host;
    private $db_name;
    private $db_user;
    private $db_password;
    private $db_port;

    private $table;

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
    
    public function getTable():string{
        return $this->table;
    }

    // Requête pour récupèrer toutes les lignes d'une table
    public function findAll(){
        $sql = "SELECT * FROM ".$this->table;
        $stmt = $this->_connexion->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->_connexion->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Requête pour récupèrer une ligne d'une table
    public function find(int $id){
        $sql = "SELECT * FROM ". $this->table . " WHERE id = ?;";
        return $this->_connexion->execute_query($sql, [$id])->fetch_assoc();
    }

    // Récupère une ligne par rapport aux critères précisé 
    public function findOneBy(array $criteria):?array{
        $result = $this->createQuery($criteria);
        return $this->_connexion->execute_query($result[0],$result[1])->fetch_assoc();
    }

    // Récupère toutes lignes par rapport aux critères précisé 
    public function findBy(array $criteria):array{
        $result = $this->createQuery($criteria);
        return $this->_connexion->execute_query($result[0],$result[1])->fetch_all(MYSQLI_ASSOC);
    }

    
    private function createQuery(array $criteria, array $orderBy = null):array{
        $conditionString = "";
        $conditionsValues = array();
        $index = 0;
        foreach ($criteria as $key => $value){
            if($index !== 0){
                $conditionString .= " AND ";
            }
            $conditionString .= $key . " = ? ";
            array_push($conditionsValues,$value);
            $index++;
        }
        if($orderBy !== NULL){
            $columnName = array_keys($orderBy)[0];
            array_push($conditionsValues,array_values($orderBy)[0]);
            $conditionString .= "ORDER BY ".$columnName. " = ?"; 
        }
        $conditionString .= ";";
        $sql = "SELECT * FROM ". $this->table . " WHERE ". $conditionString;
        return array($sql,$conditionsValues);
    }
    
}